<?php
// Optimize HTML output buffering for Speed Index
if (!defined('ABSPATH')) exit;

/**
 * Performance Optimization
 * 
 * This file contains all speed and performance optimization code including:
 * - Critical CSS inlining
 * - Font optimization (system fonts, preload, font-display)
 * - Stylesheet & script async/defer loading
 * - Image lazy loading and optimization
 * - HTML minification
 * - Query and resource dequeuing
 */

// Defer FontAwesome CSS loading - not critical for LCP
// add_filter('style_loader_tag', 'defer_fontawesome_loading', 10, 2);
function defer_fontawesome_loading($tag, $handle) {
    if ('theme-fontawesome' === $handle) {
        // Load FontAwesome asynchronously using print media trick + preload
        $tag = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $tag);
        $tag = str_replace('rel=', 'rel="preload" as="style" ', $tag);
        return $tag . "<noscript>" . str_replace(array("media='print' onload=\"this.media='all'\"", 'rel="preload" as="style" '), array("media='all'", 'rel='), $tag) . "</noscript>\n";
    }
    return $tag;
}

// Optimize script loading - aggressive defer strategy for Speed Index
// add_filter('script_loader_tag', 'optimize_script_loading', 10, 3);
function optimize_script_loading($tag, $handle, $src) {
    // Critical scripts - load synchronously (only Core functionality)
    $critical_scripts = array('jquery', 'wp-frontend');
    
    // Defer medium-priority scripts - DOM parse done, before DOMContentLoaded
    // These can wait a few ms but should run before interactive events
    $defer_scripts = array(
        'owl-carousel',      // Carousel functionality
        'fancyboxjs',        // Lightbox/modal
        'jquery-ui-accordion',
        'jquery-ui-tabs',
        'jquery-ui-datepicker',
        'google-map-init',   // Maps initialization (dependencies ready)
        'my_loadmore',       // Load-more AJAX
        'slick',             // Secondary slider
        'theme-script'       // Custom theme code
    );
    
    // Async scripts - load in parallel, no render blocking
    // These are independent and don't need DOM ready
    $async_scripts = array(
        'google-map-api'     // Google Maps API (independent)
    );
    
    if (in_array($handle, $defer_scripts)) {
        // Defer: Best for interactive features - doesn't block initial paint
        return str_replace(' src=', ' defer src=', $tag);
    } elseif (in_array($handle, $async_scripts)) {
        // Async: Best for 3rd-party APIs that don't affect page render
        return str_replace(' src=', ' async src=', $tag);
    }
    
    return $tag;
}

//add_action('init', 'jquery_in_footer', 0);
function jquery_in_footer() {
    if (!is_admin()) {
        wp_deregister_script('jquery');

        // load the local copy of jQuery in the footer
        // wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), false, null, true);
        wp_register_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', false, null, false);
        wp_enqueue_script('jquery');
    }

}
// Optimize WordPress query performance for faster initial page load
// add_action('pre_get_posts', 'optimize_main_query');
function optimize_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Limit posts per page to reduce query time
        if (is_home() || is_archive()) {
            $query->set('posts_per_page', 12);
        }
        // Don't load unnecessary post meta on list queries
        $query->set('no_found_rows', false);
    }
}

// Aggressive lazy loading for images + responsive image optimization (Speed Index)
// add_filter('the_content', 'add_lazy_loading_to_images', 15);
function add_lazy_loading_to_images($content) {
    // Add lazy loading to images, but skip critical ones
    $content = preg_replace_callback(
        '/<img\s+([^>]*?)src="([^"]*?)"([^>]*?)>/i',
        function($matches) {
            // Never lazy load images with fetchpriority (above-the-fold images)
            if (preg_match('/fetchpriority/', $matches[0])) {
                return $matches[0];
            }
            
            // Skip if already has loading attribute
            if (preg_match('/loading=/', $matches[0])) {
                return $matches[0];
            }
            
            // Add lazy loading for below-the-fold images with async decoding
            // Also ensure proper width/height for CLS prevention
            $img = '<img ' . $matches[1] . 'src="' . $matches[2] . '"';
            
            // Add width/height if missing (prevents Cumulative Layout Shift)
            if (!preg_match('/\s(width|height)=/i', $matches[1] . $matches[3])) {
                $img .= ' style="max-width: 100%; height: auto;"';
            }
            
            // Add lazy loading + async decoding for performance
            $img .= ' loading="lazy" decoding="async"' . $matches[3] . '>';
            return $img;
        },
        $content
    );
    
    return $content;
}

// Optimize image delivery - add width/height attributes to prevent CLS + lazy loading
// add_filter('wp_get_attachment_image_attributes', 'optimize_image_attributes', 10, 3);
function optimize_image_attributes($attr, $attachment, $size) {
    // Ensure aspect ratio is set to prevent layout shifts
    if (!empty($attr['width']) && !empty($attr['height'])) {
        $attr['style'] = (isset($attr['style']) ? $attr['style'] . '; ' : '') . 'aspect-ratio: ' . $attr['width'] . ' / ' . $attr['height'] . ';';
    }
    
    // Add decoding=async for non-critical images to speed up rendering
    if (!isset($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }
    
    // Add loading=lazy for images below visible viewport
    if (!isset($attr['loading']) && !preg_match('/fetchpriority/', implode(' ', $attr))) {
        $attr['loading'] = 'lazy';
    }
    
    return $attr;
}

// add_filter( 'preconnect_external_domain_exclusions', 'cf_preconnect_external_domain_exclusions' );
function cf_preconnect_external_domain_exclusions( $exclusion_patterns ) {
    // Add partial domain or specific string to exclude
    // $exclusion_patterns[] = 'maps.googleapis.com'; 
    $exclusion_patterns[] = 'js.zohocdn.com';
    $exclusion_patterns[] = 'css.zohocdn.com';
    $exclusion_patterns[] = 'salesiq.zoho.com';
    $exclusion_patterns[] = 'salesiq.zohopublic.com';
    $exclusion_patterns[] = 'static.zohocdn.com';
    $exclusion_patterns[] = 'zohocdn';
    $exclusion_patterns[] = 'zoho';
    return $exclusion_patterns;
}

//add_action('wp_head', 'remove_zoho_preconnect', 0);
function remove_zoho_preconnect()
{ ?>
    <script>
    (function () {
        const blockedDomains = [
            'zohocdn.com',
            'zoho.com',
            'zohopublic.com'
        ];

        function removePreconnect(node) {
            if (
                node.tagName === 'LINK' &&
                node.rel === 'preconnect' &&
                blockedDomains.some(domain => node.href.includes(domain))
            ) {
                node.parentNode.removeChild(node);
                console.log('Removed preconnect:', node.href);
            }
        }

        document.querySelectorAll('link[rel="preconnect"]').forEach(removePreconnect);

        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    console.log('New node added:', node.tagName);
                    removePreconnect(node);
                });
            });
        });

        observer.observe(document.head, { childList: true });
    })();
    </script>
<?php
}

// Start output buffering at the earliest point for aggressive minification
// if (!is_admin()) {
//     ob_start('lcp_speed_index_optimize');
// }

// function lcp_speed_index_optimize($html) {
//     if (defined('WP_DEBUG') && WP_DEBUG) {
//         return $html;
//     }
    
//     // Aggressive HTML minification - reduces page size for faster initial load
//     // Remove newlines and extra whitespace between tags
//     $html = preg_replace('/>\s+</', '><', $html);
    
//     // Remove comments (except IE conditionals)
//     $html = preg_replace('/<!--([\s\S]*?)-->/i', '', $html);
    
//     // Remove multiple spaces
//     $html = preg_replace('/[\s]{2,}/', ' ', $html);
    
//     return $html;
// }
