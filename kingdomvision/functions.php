<?php

$debug = isset($_GET['debug']) && current_user_can('manage_options');
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

define('THEME_PATH', get_template_directory());
define('THEME_URL', get_template_directory_uri());

// Automatically include all PHP files from the /functions directory
function include_all_functions_files()
{
    $functions_path = THEME_PATH . '/functions/';

    // Check if folder exists
    if (is_dir($functions_path)) {
        foreach (glob($functions_path . '*.php') as $file) {
            require_once $file;
        }
    }
}
include_all_functions_files();

require_once THEME_PATH . '/maxmind/vendor/autoload.php';

if (!function_exists('is_val')) {
    function is_val($var, $key = null)
    {
        if (empty($key)) {
            if (isset($var) && !empty($var)) {
                return $var;
            }
        } else {
            if (is_array($var) && array_key_exists($key, $var)) {
                return is_val($var[$key]);
            } else if (is_object($var) && property_exists($var, $key)) {
                return is_val($var->$key);
            }
        }

        return;
    }
}

use GeoIp2\Database\Reader;

function pre($value, $exit = 0)
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
    if ($exit)
        exit;
}

function cf_geo_location()
{
    // 1. Use existing cookie (same as JS)
    if (!empty($_COOKIE['cc_format'])) {
        return strtoupper($_COOKIE['cc_format']);
    }

    $isoCode = 'US'; // default fallback

    // 2. Cloudflare (fastest)
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        $isoCode = $_SERVER['HTTP_CF_IPCOUNTRY'];
    } else {
        // 3. MaxMind fallback
        try {
            $dbPath = THEME_PATH . '/maxmind/GeoLite2-Country.mmdb';

            if (file_exists($dbPath)) {
                $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];

                $reader = new Reader($dbPath);
                $record = $reader->country($ip);

                if (!empty($record->country->isoCode)) {
                    $isoCode = $record->country->isoCode;
                }
            }
        } catch (\Throwable $e) {
            // silent fallback
            $isoCode = 'US';
        }
    }

    // 4. Currency mapping (simple & correct)
    if ($isoCode == 'EU') {
        $defaultCurrency = 'EUR';
    } else if ($isoCode == 'AU') {
        $defaultCurrency = 'AUD';
    } else if ($isoCode == 'GB') {
        $defaultCurrency = 'GBP';
    } else {
        $defaultCurrency = 'USD';
    }


    // // 5. Save cookie (30 days) - same as JS
    // if (!headers_sent()) {
    //     setcookie('cc_format', $defaultCurrency, time() + (30 * 24 * 60 * 60), '/');
    // }

    return $defaultCurrency;
}

// function to round the number
function roundFunation($n)
{
    $n = str_replace(',', "", $n);
    // Smaller multiple
    $a = (int)($n / 10) * 10;

    // Larger multiple
    $b = ($a + 10);

    // Return of closest of two
    return number_format(($n - $a) ? $b : $a);
}

// Add Theme Files
function theme_files()
{
    // Theme Files
    wp_register_style('theme-style', get_stylesheet_uri(), false, filemtime(get_theme_file_path('style.css')));
    wp_enqueue_style('theme-style');

    wp_register_style('theme-responsive', THEME_URL . '/css/responsive.css', false, filemtime(get_theme_file_path('/css/responsive.css')));
    wp_enqueue_style('theme-responsive');

    // Slick Slider Files
    wp_register_style('slick',  THEME_URL . '/slick/slick.css', false, '2.2.1');
    wp_enqueue_style('slick');
    wp_register_script('slick',  THEME_URL . '/slick/slick.min.js', array('jquery'), '2.2.1', true);
    wp_enqueue_script('slick');

    //Kv Script
    wp_register_script('kv-script', THEME_URL . '/js/kv-script.js', array('jquery'),  filemtime(get_theme_file_path('/js/kv-script.js')), true);
    wp_enqueue_script('kv-script');

    // calander-kv
    wp_register_script('calander-kv', THEME_URL . '/js/kv-calander.js', array('jquery'),  '1.2', true);
    wp_enqueue_script('calander-kv');

    // Cloudflare Stream SDK
    wp_enqueue_script('cloudflare-stream', 'https://embed.cloudflarestream.com/embed/sdk.latest.js', array(), null, true);

    // Fancybox
    wp_register_style('fancybox',  'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css', false);
    wp_enqueue_style('fancybox');
    wp_register_script('fancybox',  'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js', array('jquery'), '3.1.6', true);
    wp_enqueue_script('fancybox');

    // Font Awesome
    wp_register_style('theme-fontawesome',  THEME_URL . '/fontawesome/css/all.css', false);
    wp_enqueue_style('theme-fontawesome');

    // Google API
    $insertGoogleApi = get_field('insert_google_api', 'option');
    if($insertGoogleApi){        
        wp_register_script(
            'google-map-api',
            $insertGoogleApi,
            [],
            null,
            true
        );

        wp_register_script(
            'google-map-init',
            THEME_URL . '/js/google-maps.js',
            array('google-map-api'), // <-- FIXED handle
            '1.0',
            true
        );
    }

    if (get_post_type() == 'product' || get_post_type() == 'experiences' || get_post_type() == 'ship_information' || get_post_type() == 'hotel_information') {

        wp_enqueue_script('google-map-api');
        wp_enqueue_script('google-map-init');
    }

    $googleApiChoose = get_field('choose_google_api', 'option');

    if ( $insertGoogleApi && (is_array($googleApiChoose) && !empty($googleApiChoose)) || is_single() ) {

        wp_enqueue_script('google-map-api');
        wp_enqueue_script('google-map-init');
        if (is_array($googleApiChoose)) {
            foreach ($googleApiChoose as $post_obj) {
                if (get_the_ID() === $post_obj->ID) {
                    wp_enqueue_script('google-map-api');
                    wp_enqueue_script('google-map-init');
                    break;
                }
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'theme_files');

function add_defer_to_styles( $html, $handle, $href, $media ) {
    // Exact handles 
    $blocking_styles = array( 
        'slick',             
        'fancybox',          
        'theme-fontawesome'  
    );
 
    if ( in_array( $handle, $blocking_styles ) ) {
        return '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" media="' . $media . '">' . 
               '<noscript><link rel="stylesheet" href="' . $href . '" media="' . $media . '"></noscript>';
    }
    return $html;
}
add_filter( 'style_loader_tag', 'add_defer_to_styles', 10, 4 );

function addCspHeader() {
    // Ek basic default-src policy for inline scripts aur safe sources allow
    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https:;");
}
// add_action('send_headers', 'addCspHeader');

add_action('wp_footer', function() {
    if (wp_script_is('kv-script', 'enqueued')) {
        $base_currency = get_field('base_currency', 'option') ?: 'USD';
        wp_localize_script('kv-script', 'kingdomVision', array(
            'ajaxurl'  => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce( 'filter_blog_nonce' ),
            'themeUrl' => get_template_directory_uri(),
            'baseCurrency' => $base_currency,
            'videos' => !empty($GLOBALS['ywCloudVideos']) ? $GLOBALS['ywCloudVideos'] : array(),
            'images' => !empty($GLOBALS['desktopMobileImage']) ? $GLOBALS['desktopMobileImage'] : array()
        ));
    }
}, 5);


// Currency Script
function currency_enqueue_scripts()
{
    wp_enqueue_script('kv-currency', THEME_URL . '/js/kv-currency.js', ['jquery'], filemtime(get_theme_file_path('/js/kv-currency.js')), true);

    // // Static options — cache for 12 hours
    // $base_currency = get_transient('kv_base_currency');
    // if ($base_currency === false) {
    //     $base_currency = get_field('base_currency', 'option') ?: 'GBP';
    //     set_transient('kv_base_currency', $base_currency, 12 * HOUR_IN_SECONDS);
    // }
    // Base currency from theme option (ACF)
    $base_currency = get_field('base_currency', 'option') ?: 'USD';

    // Custom pricing (FULL ARRAY, not single currency)
    $custom_pricing = get_field('custom_pricing', 'option');

    // $custom_pricing = get_transient('kv_custom_pricing');
    // if ($custom_pricing === false) {
    //     $custom_pricing = get_field('custom_pricing', 'option') ?: [];
    //     set_transient('kv_custom_pricing', $custom_pricing, 12 * HOUR_IN_SECONDS);
    // }

    // Rate conversion already stored in wp_options — fast, no change needed
    $rate_conversion = get_option('cur_rate_conversion', []);

    // Geo: CF header is free (no file open), MaxMind only as fallback
    $default_currency = cf_geo_location();

    // Cache geo per IP — free if CF header exists, cached if MaxMind fallback
    $cf_country = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? strtoupper(sanitize_text_field($_SERVER['HTTP_CF_IPCOUNTRY'])) : null;

    wp_localize_script('kv-currency', 'ccdata', [
        'rates'      => $rate_conversion,
        'custom'     => $custom_pricing,
        'base'       => strtoupper($base_currency),
        'geo'        => $default_currency,
        'cf_country' => $cf_country,
    ]);
}
add_action('wp_enqueue_scripts', 'currency_enqueue_scripts');

add_action('wp_head', 'cf_preload_fonts', 0);
function cf_preload_fonts()
{ ?>
    <link rel="preload" as="font" href="<?php echo THEME_URL; ?>/fonts/MinervaModern-Regular.woff2" crossorigin>
<?php
}

// Enable Classic Editor
add_filter('use_block_editor_for_post', '__return_false', 10);

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter('gutenberg_use_widgets_block_editor', '__return_false');

// Disables the block editor from managing widgets.
add_filter('use_widgets_block_editor', '__return_false');

// Disable image tag auto side
add_filter('wp_img_tag_add_auto_sizes', '__return_false');

// ================================
// ACF OPTIONS PAGES
// ================================
add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'     => 'Theme Options',
            'menu_title'    => 'Theme Options',
            'menu_slug'     => 'theme-options',
            'capability'    => 'manage_options',
            'redirect'        => false
        ));

        acf_add_options_page(array(
            'page_title' => 'Locations',
            'menu_title' => 'Locations',
            'menu_slug' => 'schema-location',
            'capability' => 'edit_posts',
            'redirect' => false,
        ));
    }
});

// Register Sidebar
add_action('widgets_init', 'kv_widgets_init');
function kv_widgets_init()
{
    $sidebar_attr = array(
        'name'             => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widgettitle">',
        'after_title'   => '</h3>',
    );
    $sidebar_id = 0;
    $gdl_sidebar = array("Blog");
    foreach ($gdl_sidebar as $sidebar_name) {
        $sidebar_attr['name'] = $sidebar_name;
        $sidebar_attr['id'] = 'custom-sidebar' . $sidebar_id++;
        register_sidebar($sidebar_attr);
    }
}

// Register Navigation
function register_menu()
{
    register_nav_menu('main-menu', __('Main Menu'));
}
add_action('init', 'register_menu');


// Featured Image Function
add_theme_support('post-thumbnails');

// Woocommerce Support
add_theme_support('woocommerce');

// SVG Allow For Media Library
function my_custom_mime_types($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    $mimes['doc']  = 'application/msword';
    
    unset($mimes['exe']); // Security Purpose
    return $mimes;
}
add_filter('upload_mimes', 'my_custom_mime_types');

// 2. wp_handle_upload_prefilter used for sanitize
function secure_sanitize_svg_upload($file) {
    // Check If the file is SVG
    if (isset($file['type']) && $file['type'] === 'image/svg+xml') {
        
        $file_path = $file['tmp_name'];
        
        if (file_exists($file_path)) {
            // SVG file content
            $svg_content = file_get_contents($file_path);
            
            // --- SANITIZATION LOGIC ---
            $svg_content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $svg_content);
            $svg_content = preg_replace('/on\w+\s*=\s*"[^"]*"/i', '', $svg_content);
            $svg_content = preg_replace('/on\w+\s*=\s*\'[^\']*\'/i', '', $svg_content);
            $svg_content = preg_replace('/on\w+\s*=\s*[^\s>]+/i', '', $svg_content);
            
            // Filter javascript: pseudo-protocol
            $svg_content = preg_replace('/href\s*=\s*["\']\s*javascript:[^"\']*["\']/i', 'href="#"', $svg_content);
            
            // Cleaned content 
            file_put_contents($file_path, $svg_content);
        }
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'secure_sanitize_svg_upload');

//Current Year Shortcode [year]
function currentYear($atts)
{
    return date('Y');
}
add_shortcode('year', 'currentYear');

// Delay ACF textdomain loading until init
add_action('acf/init', function () {
    load_plugin_textdomain('acf', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});

// add default image setting to ACF image fields
add_action('acf/render_field_settings/type=image', 'add_default_value_to_image_field');
function add_default_value_to_image_field($field)
{
    acf_render_field_setting($field, array(
        'label' => 'Default Image',
        'instructions' => 'Appears when creating a new post',
        'type' => 'image',
        'name' => 'default_value',
    ));
}
// add default link setting to ACF Link fields
add_action('acf/render_field_settings/type=link', 'add_default_value_to_link_field');
function add_default_value_to_link_field($field)
{
    acf_render_field_setting($field, array(
        'label' => __('Default Link', 'kingdomvision'),
        'instructions' => __('Appears when creating a new post', 'kingdomvision'),
        'type' => 'group',
        'name' => 'default_value',
        'sub_fields' => array(
            array(
                'label' => __('URL', 'kingdomvision'),
                'type' => 'url',
                'name' => 'url',
            ),
            array(
                'label' => __('Title', 'kingdomvision'),
                'type' => 'text',
                'name' => 'title',
            )
        ),
    ));
}


// Css Variable
add_action('wp_head', 'ufg_dynamic_css_vars', 99);
function ufg_dynamic_css_vars()
{
    // $newsletter_color = get_field('footer_newsletter', 'option')['newsletter_text_color'] ?? '';
    $colors = get_field('colors', 'option') ?: [];
    $fonts  = get_field('font_sizes', 'option') ?: [];
    $mobile_font_sizes  = get_field('mobile_font_sizes', 'option') ?: [];
    global $ufg_global_fonts;
    $ufg_global_fonts = $fonts;
    // Page Banner Opacity
    $overlay_transparency = get_field('overlay_transparency', get_the_ID());
    $global_overlay_transparency = get_field('global_overlay_transparency', 'option');

    if (empty($colors) && empty($fonts)) return;

    echo "<style id='acf-css-vars'>:root {\n";    
    // if (!empty($newsletter_color)) {
    //     echo "  --newsletter-text-color: " . esc_attr($newsletter_color) . ";\n";
    // }
    if (!empty($colors['body_color'])) {
        echo "  --body-color: " . esc_attr($colors['body_color']) . ";\n";
    }
    if (!empty($colors['heading_color'])) {
        echo "  --heading-color: " . esc_attr($colors['heading_color']) . ";\n";
    }
    if (!empty($colors['anchor_color'])) {
        echo "  --awb-link-color: " . esc_attr($colors['anchor_color']) . ";\n";
    }
    if (!empty($colors['anchor_hover_color'])) {
        echo "  --awb-link-hover-color: " . esc_attr($colors['anchor_hover_color']) . ";\n";
    }
    if (!empty($colors['jbg_color'])) {
        echo "  --jumplink-bg-color: " . esc_attr($colors['jbg_color']) . ";\n";
    }

    if (!empty($colors['jfont_color'])) {
        echo "  --jumplink-color: " . esc_attr($colors['jfont_color']) . ";\n";
    }

    if (!empty($colors['blog_anchor'])) {
        echo "  --blog-anchor-color: " . esc_attr($colors['blog_anchor']) . ";\n";
    }

    if (!empty($colors['blog_anchor_hover'])) {
        echo "  --blog-anchor-hover: " . esc_attr($colors['blog_anchor_hover']) . ";\n";
    }

    if (!empty($fonts['body_size'])) {
        echo "  --body-font-size: " . esc_attr($fonts['body_size']) . ";\n";
    }

    if (!empty($fonts['jfont_size'])) {
        echo "  --jumplink-font-size: " . esc_attr($fonts['jfont_size']) . ";\n";
    }

    // Page Banner Opacity
    if (!empty($overlay_transparency) && $overlay_transparency !== 0) {
        echo "  --banner-opacity: 0." . esc_attr($overlay_transparency) . ";\n";
    } else {
        echo "  --banner-opacity: 0." . esc_attr($global_overlay_transparency) . ";\n";
    }

    foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
        if (!empty($fonts["{$tag}_size"])) {
            echo "  --{$tag}-font-size: " . esc_attr($fonts["{$tag}_size"]) . ";\n";
        }
    }

    // Mobile Font Size
    if (!empty($mobile_font_sizes['body_size'])) {
        echo "  --mob-body-font-size: " . esc_attr($mobile_font_sizes['body_size']) . ";\n";
    }

    if (!empty($mobile_font_sizes['jfont_size'])) {
        echo "  --mob-jumplink-font-size: " . esc_attr($mobile_font_sizes['jfont_size']) . ";\n";
    }

    foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $mob_tag) {
        if (!empty($mobile_font_sizes["{$mob_tag}_size"])) {
            echo "  --mob-{$mob_tag}-font-size: " . esc_attr($mobile_font_sizes["{$mob_tag}_size"]) . ";\n";
        }
    }


    echo "}\n</style>";
}

// Add Static Data in ACF fields
function ACF_Backend_Data_handle($field)
{

    global $post;

    if ($field['name'] == 'heading_tags') {
        $field['choices'] = array(
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
        );
        $field['default_value'] = $field['default_value'] ?: 'h2';
    }

    if ($field['name'] == 'heading_alignment') {
        $field['choices'] = array(
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        );
        $field['default_value'] = $field['default_value'] ?: 'center';
    }

    if ($field['name'] == 'select_post_type') {
        $field['choices'] = array(
            'ship_information' => 'Ship Information',
            'hotel_information' => 'Hotel Information',
            'products' => 'Products',
            'post' => 'Post',
        );
        $field['default_value'] = 'products';
    }

    if ($field['name'] == 'section_text_color') {
        $field['choices'] = array(
            'light' => 'Light',
            'dark' => 'Dark',
        );
        $field['default_value'] = $field['default_value'] ?: 'dark';
        $field['instructions'] =
            'If you set a dark background color or image, you will need to select light in this option.<br>If you set a light background color or image, you will need to select dark in this option.';
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '40';
    }

    if ($field['name'] == 'background_option') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '30';
    }

    if ($field['name'] == 'background_color') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '30';
    }

    if ($field['name'] == 'background_image') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '30';
    }

    // Padding & Margin
    if ($field['name'] == 'padding_top') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '16';
    }
    if ($field['name'] == 'padding_bottom') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '16';
    }
    if ($field['name'] == 'margin_top') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '16';
    }
    if ($field['name'] == 'margin_bottom') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '16';
    }

    // Padding & Margin
    if ($field['name'] == 'show_in_desktop') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '16';
    }
    if ($field['name'] == 'show_in_mobile') {
        $field['wrapper']['width'] = $field['wrapper']['width'] ?: '16';
    }

    return $field;
}
add_filter('acf/load_field', 'ACF_Backend_Data_handle');

// Add Color in ACF COLOR PICKER
function acfColorPicker()
{
?>
    <script type="text/javascript">
        jQuery(function($) {
            acf.add_filter('color_picker_args', function(args, $field) {
                // Add your custom hex codes here
                args.palettes = ['#171c3b', '#bb5b3e', '#f28748', '#f2af5c', '#f2e2c4', '#fcf4e7', '#ffffff', '#000000'];
                return args;
            });

        });
    </script>
    <?php
}
add_action('acf/input/admin_footer', 'acfColorPicker');

// Dyanmic Team Member from Team Specialists
add_filter('acf/load_field/name=select_member', 'acf_load_team_members_as_select_choices');
function acf_load_team_members_as_select_choices($field)
{
    $field['choices'] = array(); // reset choices

    if (have_rows('team_members', 'option')) {
        while (have_rows('team_members', 'option')) {
            the_row();
            $name = get_sub_field('name');

            if ($name) {
                // value => label
                $field['choices'][$name] = $name;
            }
        }
    }

    return $field;
}

// Dynamic Background From Section
function backgroundFromSection($section)
{
    $background_option = $section['background_option'] ?? '';
    $background_image = $section['background_image'] ?? '';
    $background_color = $section['background_color'] ?? '';

    $padding_top = $section['padding_top'] ?? '';
    $padding_bottom = $section['padding_bottom'] ?? '';
    $margin_top = $section['margin_top'] ?? '';
    $margin_bottom = $section['margin_bottom'] ?? '';

    $sectionSpacing = '';
    if ($padding_top !== '') {
        $sectionSpacing .= "padding-top: {$padding_top}px;";
    }
    if ($padding_bottom !== '') {
        $sectionSpacing .= "padding-bottom: {$padding_bottom}px;";
    }
    if ($margin_top !== '') {
        $sectionSpacing .= "margin-top: {$margin_top}px;";
    }
    if ($margin_bottom !== '') {
        $sectionSpacing .= "margin-bottom: {$margin_bottom}px;";
    }

    $show_in_desktop = $section['show_in_desktop'] ?? true;
    $show_in_mobile = $section['show_in_mobile'] ?? true;

    $result = '';

    // Set image HTML
    if ($background_option === 'img' && $background_image) {

        $image_url = wp_get_attachment_image_url($background_image, 'full');
        $result .= 'style="background: url(' . esc_url($image_url) . ')no-repeat center/cover; ' . $sectionSpacing . '" 
                    showDesktop="' . ($show_in_desktop ? 'showOnDesktop' : 'hideOnDesktop') . '" showMobile="' . ($show_in_mobile ? 'showOnMobile' : 'hideOnMobile') . '" ';
    } else {
        if ($background_color) {

            $result .= 'style="background: ' . esc_attr($background_color) . '; ' . $sectionSpacing . '" 
                        showDesktop="' . ($show_in_desktop ? 'showOnDesktop' : 'hideOnDesktop') . '" showMobile="' . ($show_in_mobile ? 'showOnMobile' : 'hideOnMobile') . '" ';
        } else {
            $result .= 'style="' . $sectionSpacing . '" 
                        showDesktop="' . ($show_in_desktop ? 'showOnDesktop' : 'hideOnDesktop') . '" showMobile="' . ($show_in_mobile ? 'showOnMobile' : 'hideOnMobile') . '" ';
        }
    }

    return $result;
}


// Dynamic Heading From Section
function HeadingFromSection($section, $postID = 0, $class = '', $getCookieData = false)
{

    $heading_tags = !empty($section['heading_tags']) ? $section['heading_tags'] : ($postID ? get_field('heading_tags', $postID) : '');
    $heading_alignment = !empty($section['heading_alignment']) ? $section['heading_alignment'] : ($postID ? get_field('heading_alignment', $postID) : '');
    $heading_text = !empty($section['heading_text']) ? $section['heading_text'] : ($postID ? get_field('heading_text', $postID) : '');
    $heading_color = !empty($section['heading_color']) ? $section['heading_color'] : ($postID ? get_field('heading_color', $postID) : '');
    $heading_size =  !empty($section['heading_size']) ? $section['heading_size'] : ($postID ? get_field('heading_size', $postID) : '');

    $heading_break_text =  !empty($section['heading_break_text']) ? $section['heading_break_text'] : ($postID ? get_field('heading_break_text', $postID) : '');
    $heading_sub_text =  !empty($section['heading_sub_text']) ? $section['heading_sub_text'] : ($postID ? get_field('heading_sub_text', $postID) : '');

    $style = '';
    if ($heading_color || $heading_size) {
        $style = 'style="' . ($heading_color ? 'color: ' . esc_attr($heading_color) . ';' : '') . ' ' . ($heading_size ? 'font-size: ' . esc_attr($heading_size) . 'px;' : '') . '"';
    }

    $printable = '';
    if ($heading_text && $heading_break_text) {
        $printable .= '<div class="headingWrapper ' . esc_attr($class) . '">';
        if ($heading_tags == 'h1') {
            $printable .= '<h1 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . ' <span class="'.($getCookieData == true ? 'cookiesTitle' : '').'"></span> ' . esc_html($heading_break_text) . '</h1>';
        } elseif ($heading_tags == 'h3') {
            $printable .= '<h3 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . ' <span class="'.($getCookieData == true ? 'cookiesTitle' : '').'"></span> ' . esc_html($heading_break_text) . '</h3>';
        } elseif ($heading_tags == 'h4') {
            $printable .= '<h4 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . ' <span class="'.($getCookieData == true ? 'cookiesTitle' : '').'"></span> ' . esc_html($heading_break_text) . '</h4>';
        } elseif ($heading_tags == 'h5') {
            $printable .= '<h5 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . ' <span class="'.($getCookieData == true ? 'cookiesTitle' : '').'"></span> ' . esc_html($heading_break_text) . '</h5>';
        } elseif ($heading_tags == 'h6') {
            $printable .= '<h6 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . ' <span class="'.($getCookieData == true ? 'cookiesTitle' : '').'"></span> ' . esc_html($heading_break_text) . '</h6>';
        } else {
            $printable .= '<h2 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . ' <span class="'.($getCookieData == true ? 'cookiesTitle' : '').'"></span> ' . esc_html($heading_break_text) . '</h2>';
        }
        $printable .= '<h3 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_sub_text) . '</h3>';
        $printable .= '</div>'; #headingWrapper
    } else {
        if ($heading_text) {
            $printable .= '<div class="headingWrapper ' . esc_attr($class) . '">';
            if ($heading_tags == 'h1') {
                $printable .= '<h1 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . '</h1>';
            } elseif ($heading_tags == 'h3') {
                $printable .= '<h3 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . '</h3>';
            } elseif ($heading_tags == 'h4') {
                $printable .= '<h4 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . '</h4>';
            } elseif ($heading_tags == 'h5') {
                $printable .= '<h5 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . '</h5>';
            } elseif ($heading_tags == 'h6') {
                $printable .= '<h6 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . '</h6>';
            } else {
                $printable .= '<h2 class="heading ' . $heading_alignment . '" ' . ($style ? $style : '') . '>' . esc_html($heading_text) . '</h2>';
            }
            $printable .= '</div>'; #headingWrapper
        }
    }
    return $printable;
}

function dynamicBtnFromSection($section, $postID = 0)
{
    // $button = $section['button'] ?? ($section['cta_button'] ?? null);
    $button = $section['button'] ?? $section['cta_button'] ?? ($postID ? get_field('button', $postID) : '') ?? '';
    // if(isset($_GET['button']) && $_GET['button'] == 1){
    //     echo $button;
    // }
    $printable = '';
    if (!empty($button) && is_array($button) && !empty($button['url']) || !empty($button['title'])) {
        $link_url = $button['url'];
        $link_title = $button['title'];
        $link_target = $button['target'] ? $button['target'] : '_self';
        $printable .= '<div class="ctaButton">';
        $printable .= '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" class="btn enquiryNow" role="button" aria-label="' . esc_attr($link_title) . '" data-postTitle="' . esc_attr(get_the_title()) . '" data-postID="' . esc_attr(get_the_ID()) . '" >' . esc_html($link_title) . '</a>';
        $printable .= '</div>'; #ctaButton
    }
    return $printable;
}

// hex2rgba
function hex2rgba($color, $opacity = 1)
{
    $color = str_replace('#', '', $color);
    if (strlen($color) == 3) {
        $r = hexdec(substr($color, 0, 1) . substr($color, 0, 1));
        $g = hexdec(substr($color, 1, 1) . substr($color, 1, 1));
        $b = hexdec(substr($color, 2, 1) . substr($color, 2, 1));
    } else {
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
    }
    return "rgba($r, $g, $b, $opacity)";
}



// Slider Arrow Custom
function slickSliderArrows($className)
{
    $printable = '';
    $printable .= '<div class="SliderArrows ' . esc_attr($className) . ' " data-sliderClassName="' . esc_attr($className) . '" role="group" aria-label="Slide Navigation">';
    $printable .= '<div class="angel-arrow-left slick-arrow " role="button" aria-label="Previous Slide" >';
    $printable .= '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none">';
    $printable .= '<path d="M1.19002 4.3438C1.86611 4.14308 2.63653 3.72105 3.25497 3.4174C3.68997 3.20124 4.09877 2.95419 4.48136 2.67113C5.4457 1.96089 6.23708 1.04478 6.78739 0H8.3754C8.3754 0 8.3754 0.0102934 8.3754 0.01544C7.70456 1.56459 6.59871 2.90273 5.20985 3.88574C4.89015 4.1122 4.57045 4.34894 4.21931 4.51879H15V5.48121H4.1669C4.52853 5.67164 4.87443 5.88266 5.20461 6.11426C6.59347 7.09727 7.69932 8.43541 8.37016 9.98456C8.37016 9.98456 8.37016 9.99485 8.37016 10H6.78215C6.23184 8.95008 5.43521 8.03911 4.47612 7.32887C4.09352 7.04581 3.67949 6.79876 3.24973 6.5826C2.63653 6.27895 2.04954 5.89295 1.36822 5.69223C0.131346 5.29079 0.000320435 5.02316 0.000320435 5.00772C0.000320435 4.99228 -0.0520887 4.76068 1.18478 4.35924L1.19002 4.3438Z" fill="#FCF4E7"></path>';
    $printable .= '</svg>';
    $printable .= '</div>';
    $printable .= '<div class="angel-arrow-right slick-arrow " role="button" aria-label="Next Slide" >';
    $printable .= '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none">';
    $printable .= '<path d="M13.81 4.3438C13.1339 4.14308 12.3635 3.72105 11.745 3.4174C11.31 3.20124 10.9012 2.95419 10.5186 2.67113C9.5543 1.96089 8.76292 1.04478 8.21261 0H6.6246C6.6246 0 6.6246 0.0102934 6.6246 0.01544C7.29544 1.56459 8.40129 2.90273 9.79015 3.88574C10.1098 4.1122 10.4295 4.34894 10.7807 4.51879H0V5.48121H10.8331C10.4715 5.67164 10.1256 5.88266 9.79539 6.11426C8.40653 7.09727 7.30068 8.43541 6.62984 9.98456C6.62984 9.98456 6.62984 9.99485 6.62984 10H8.21785C8.76816 8.95008 9.56479 8.03911 10.5239 7.32887C10.9065 7.04581 11.3205 6.79876 11.7503 6.5826C12.3635 6.27895 12.9505 5.89295 13.6318 5.69223C14.8687 5.29079 14.9997 5.02316 14.9997 5.00772C14.9997 4.99228 15.0521 4.76068 13.8152 4.35924L13.81 4.3438Z" fill="#FCF4E7"></path>';
    $printable .= '</svg>';
    $printable .= '</div>';
    $printable .= '</div>'; #SliderArrows
    return $printable;
}

// Get YouTube ID from any valid YouTube URL
function youtubeID($link)
{
    if (empty($link)) {
        return false;
    }
    $url_parts = parse_url(trim($link));
    // Case 1: Short youtu.be link
    if (isset($url_parts['host']) && strpos($url_parts['host'], 'youtu.be') !== false) {
        // path usually like /LjZxeSne67E
        return ltrim($url_parts['path'], '/');
    }
    // Case 2: Standard youtube.com/watch?v=ID
    if (isset($url_parts['query'])) {
        parse_str($url_parts['query'], $query_params);
        if (!empty($query_params['v'])) {
            return $query_params['v'];
        }
    }
    // Case 3: Embedded or share formats like /embed/ID or /v/ID
    if (isset($url_parts['path'])) {
        $path_parts = explode('/', trim($url_parts['path'], '/'));
        foreach ($path_parts as $part) {
            if (strlen($part) === 11) { // YouTube video IDs are always 11 characters long
                return $part;
            }
        }
    }

    return false; // Not a valid YouTube link
}

// Youtube / Wordpress Video
function ywVideoFromRepeator($rep, $key = '')
{
    $video_option = !empty($rep['video_option']) ? $rep['video_option'] : '';
    $youtube_url = !empty($rep['youtube_url']) ? $rep['youtube_url'] : '';
    $wordpress_video = !empty($rep['wordpress_video']) ? $rep['wordpress_video'] : '';
    $wordpress_thumbnail = !empty($rep['wordpress_thumbnail']) ? $rep['wordpress_thumbnail'] : '';

    $cloud_desktop_code = !empty($rep['cloud_desktop_code']) ? $rep['cloud_desktop_code'] : '';
    $cloud_desktop_poster = !empty($rep['cloud_desktop_poster']) ? $rep['cloud_desktop_poster'] : '';
    $cloud_mobile_code = !empty($rep['cloud_mobile_code']) ? $rep['cloud_mobile_code'] : '';
    $cloud_mobile_poster = !empty($rep['cloud_mobile_poster']) ? $rep['cloud_mobile_poster'] : '';


    $desktopPosterImg = 
        $cloud_desktop_poster ? 
            wp_get_attachment_image($cloud_desktop_poster, 'full', false, ['loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'nolazyload', 'width' => '1920', 'height' => '800']) : 
            getFocalImage($cloud_desktop_poster, 'cloud_desktop_poster', 'single_module', $key, '' , 'eager' , 'high');


    $mobilePosterImg = 
        $cloud_mobile_poster ? 
            getFocalImage($cloud_mobile_poster, 'cloud_mobile_poster', 'single_module', $key, '' , 'eager' , 'high') : 
            wp_get_attachment_image($cloud_mobile_poster, 'full', false, ['loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'nolazyload', 'width' => '780', 'height' => '1080']) ;

    $printable = '';
    if ($video_option == 'youtube') {
        $youtubeID = youtubeID($youtube_url);
        $printable .= '<div class="mediaWrapper youtubeVideo">';
        $printable .= '<iframe width="100%" src="https://www.youtube.com/embed/' . esc_attr($youtubeID) . '" title="Embedded YouTube video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="lazy"></iframe>';
        $printable .= '</div>'; #mediaWrapper
    } elseif ($video_option == 'cloud') {

        $cloudUniqId = 'cloud-' . rand(1000, 9999);

        // store codes for footer script
        $GLOBALS['ywCloudVideos'][] = [
            'cloudId' => $cloudUniqId,
            'desktop' => $cloud_desktop_code,
            'mobile'  => $cloud_mobile_code,
        ];

        // $desktopPosterStyle = $desktopPosterImg ? 'background: url(' . esc_url($desktopPosterImg) . ')no-repeat center/cover;' : '';
        // $mobilePosterStyle = $mobilePosterImg ? 'background: url(' . esc_url($mobilePosterImg) . ')no-repeat center/cover;' : '';

        $printable .= '<div class="mediaWrapper cloudVideo" >';
            $printable .= '<div class="cloudPadding cloudVideoDesktop"style="position: relative; padding-top: 41.65919282511211%; ">
            '.$desktopPosterImg.'
            </div>';
            $printable .= '<div class="cloudPadding cloudVideoMobile" style="position: relative; padding-top: 138.51185609157807%; ">
            '.$mobilePosterImg.'
            </div>';
        $printable .= '</div>'; #mediaWrapper

    } else {
        $poster_url = $wordpress_thumbnail ? wp_get_attachment_image_url($wordpress_thumbnail, 'full') : '';
        $printable .= '<div class="mediaWrapper wordpressVideo">';
        $printable .= '<div class="videoPadding desktopPadding" style="padding-top: 41.61434977578475%; display: none;"></div>';
        $printable .= '<div class="videoPadding mobilePadding" style="padding-top: 128.3464566929134%; display: none;"></div>';
        $printable .= wp_video_shortcode(array(
            'src'      => esc_url($wordpress_video['url']),
            'poster'   => esc_url($poster_url),
            'preload'  => 'metadata',
            'autoplay' => false,
            'loop'     => false,
            'muted'    => false,
            'attr'     => array(
                'aria-label' => 'Video content',
            ),
        ));
        $printable .= '</div>'; #mediaWrapper
    }
    return $printable;
}

// Generate Mobile & Desktop Image
function desktopMobileImage($section, $key, $placeholder = '', $lightBox = false)
{

    $desktop_image = '';
    $mobile_image = '';
    if (get_post_type() == 'hotel_information') {
        $desktop_image = !empty($section['desktop_image']) ? $section['desktop_image'] :  defaultImageID($placeholder);
        $mobile_image = !empty($section['mobile_image']) ? $section['mobile_image'] : $section['desktop_image'];
    } else {
        $desktop_image = !empty($section['desktop_image']) ? $section['desktop_image'] :  defaultImageID($placeholder);
        $mobile_image = !empty($section['mobile_image']) ? $section['mobile_image'] : mobDefaultImageID();
    }

    // Generate HTML like wp_get_attachment_image() does
    $desktop_html = '';
    $mobile_html = '';

    // Generate Focal Url
    $focalDesktopHtml = $desktop_image ? getFocalImage($desktop_image, 'desktop_image', 'single_module', $key) : wp_get_attachment_image(defaultImageID($placeholder), 'full', false, ['loading' => 'eager', 'fetchpriority' => 'high']);
    $focalMobileHtml  = $mobile_image  ? getFocalImage($mobile_image, 'mobile_image', 'single_module', $key)  : wp_get_attachment_image(mobDefaultImageID(), 'full', false, ['loading' => 'eager' , 'fetchpriority' => 'high']);

    // Global Padding
    $desktopPaddingResult = '';
    $mobilePaddingResult = '';

    
    if ($focalDesktopHtml || $focalMobileHtml) {

        $focalDesktopSize = getimagesize(getFocalImageUrl($desktop_image, 'desktop_image', 'single_module', $key));
        $focalDesktopWidth = $focalDesktopSize[0];
        $focalDesktopHeight = $focalDesktopSize[1];

        $focalMobileSize = getimagesize(getFocalImageUrl($mobile_image, 'mobile_image', 'single_module', $key));
        $focalMobileWidth = $focalMobileSize[0];
        $focalMobileHeight = $focalMobileSize[1];

        if (!empty($focalDesktopHtml) && !empty($desktop_image)) {
            if (!empty($focalDesktopWidth) && !empty($focalDesktopHeight)) {
                $desktopPaddingResult = ($focalDesktopHeight / $focalDesktopWidth) * 100;
            }
        }

        if (!empty($focalMobileSize) && !empty($mobile_image)) {
            if (!empty($focalMobileWidth) && !empty($focalMobileHeight)) {
                $mobilePaddingResult = ($focalMobileHeight / $focalMobileWidth) * 100;
            }
        }

        $desktop_html = $focalDesktopHtml;
        $mobile_html  = $focalMobileHtml;
    } else {

        if (!empty($desktop_html) && !empty($desktop_image)) {
            $meta = wp_get_attachment_metadata($desktop_image);
            if (!empty($meta['width']) && !empty($meta['height'])) {
                $desktopPaddingResult = ($meta['height'] / $meta['width']) * 100;
            }
        }

        if (!empty($mobile_html) && !empty($mobile_image)) {
            $meta = wp_get_attachment_metadata($mobile_image);
            if (!empty($meta['width']) && !empty($meta['height'])) {
                $mobilePaddingResult = ($meta['height'] / $meta['width']) * 100;
            }
        }

        $desktop_html = $desktop_image ? wp_get_attachment_image($desktop_image, 'full', false, array('loading' => 'eager' , 'fetchpriority' => 'high')) : wp_get_attachment_image(defaultImageID(), 'full', false, array('loading' => 'eager', 'fetchpriority' => 'high'));
        $mobile_html  = $mobile_image  ? wp_get_attachment_image($mobile_image, 'full', false, ['loading' => 'eager', 'fetchpriority' => 'high'])  : wp_get_attachment_image(mobDefaultImageID(), 'full', false, array('loading' => 'eager' , 'fetchpriority' => 'high'));
    }

    // =================================================================
    // ULTRA FAILSAFE REGEX INJECTION 
    // =================================================================
    
    // Desktop Image Fix
    if ( ! empty($desktop_html) ) {
        $desktop_html = preg_replace('/loading=\\s*["\'][^"\']*["\']/i', '', $desktop_html);
        $desktop_html = preg_replace('/fetchpriority=\\s*["\'][^"\']*["\']/i', '', $desktop_html);
        $desktop_html = str_replace('<img ', '<img loading="eager" fetchpriority="high" ', $desktop_html);
    }

    // Mobile Image Fix
    if ( ! empty($mobile_html) ) {
        $mobile_html = preg_replace('/loading=\\s*["\'][^"\']*["\']/i', '', $mobile_html);
        $mobile_html = preg_replace('/fetchpriority=\\s*["\'][^"\']*["\']/i', '', $mobile_html);
        $mobile_html = str_replace('<img ', '<img loading="eager" fetchpriority="high" ', $mobile_html);
    }


    // store codes for footer script
    $GLOBALS['desktopMobileImage'][] = [
        'desktop' => $desktop_html,
        'mobile'  => $mobile_html,
        'haveLightBox' => $lightBox,
    ];

    $printable = '';
    
    $printable .= '<div class="imageWrapper desktopMobileImage">';
    if ($lightBox == true) {
        $printable .= '<a data-fancybox="gallery" href="javascript:;">';
    }
    $printable .= '<div class="imgPadding imgDesktop"
                    style="padding-top: ' . esc_attr($desktopPaddingResult) . '%;"></div>';
    $printable .= '<div class="imgPadding imgMobile"
                    style="padding-top: ' . esc_attr($mobilePaddingResult) . '%; "></div>';
    if ($lightBox == true) {
        $printable .= '</a>';
    }
    $printable .= '</div>';
    return $printable;
}

// Default Image ID Function
function defaultImageID($placeholder = '')
{
    $defaultImg = '';
    if ($placeholder == 'hotel') {
        $defaultImg = get_field('hotel_placeholder_image', 'option');
    } else {
        $defaultImg = get_field('desktop_placeholder_image', 'option');
    }
    return $defaultImg;
}

// Default Image ID Function
function mobDefaultImageID()
{
    $defaultImg = get_field('mobile_placeholder_image', 'option');
    return $defaultImg;
}

// Get Single Section From The Builder
function getFlexibleSection($sections, $layout)
{
    if (empty($sections) || !is_array($sections)) {
        return [];
    }
    foreach ($sections as $section) {
        if (($section['acf_fc_layout'] ?? '') === $layout) {
            return $section;
        }
    }
    return [];
}

// 
function blogStructureFromRepeator($rep, $button_text = '', $titleColor = '', $boxColor = '', $pageIndex = 0, $repKey = 0)
{

    $is_post = (is_numeric($rep) || $rep instanceof WP_Post);
    $post_type = '';

    if ($is_post) {
        $post_id = is_numeric($rep) ? $rep : $rep->ID;
        $post_type = get_post_type($post_id);
    }

    $btntitle  = '';
    $btntarget = '';
    $staticBlogCont = '';
    $select_auhtor = '';
    $date = '';
    $listingImg = '';

    // Offer Ribbon Setting From Theme Option
    $theme_offer_ribbon = get_field('offer_ribbon', 'option') ?? [];

    if ($is_post) {
        $post_id = is_numeric($rep) ? $rep : $rep->ID;

        $getFields = get_fields($post_id);

        $blogImgID = get_post_thumbnail_id($post_id) ?: defaultImageID();
        $blogUrl   = get_the_permalink($post_id);
        $blogTitle = get_the_title($post_id);
        $blogCont  = get_post_field('post_content', $post_id) ?: '';
        $product   = isset($getFields['product']) ? $getFields['product'] : '';
        $post_ribbon  = isset($getFields['offer_ribbon']) ? $getFields['offer_ribbon'] : '';

        // Author Details
        $select_auhtor   = isset($getFields['select_member']) ? $getFields['select_member'] : '';
        $date   = isset($getFields['date']) ? $getFields['date'] : '';
        // Theme Specialist
        $teamMembersOption = isset($getFields['team_members']) ? $getFields['team_members'] : [];

        // 
        $hotels = isset($getFields['hotel_details']) ? $getFields['hotel_details'] : '';

        // Ship
        $shipBuilder = isset($getFields['product_page_builder']) ? $getFields['product_page_builder'] : '';

        // Listing Image
        $listingImg = isset($getFields['listing_image']) ? $getFields['listing_image'] : '';
    } else {
        $blogImgID = $rep['image'] ?? '';
        $blogUrl   = $rep['button']['url'] ?? '';
        $btntitle  = $rep['button']['title'] ?? '';
        $btntarget  = $rep['button']['target'] ?? '';
        $blogTitle = $rep['title'] ?? '';
        $staticBlogCont  = $rep['content'] ?? '';
        $product   = $rep['product'] ?? [];
        $post_ribbon  = $rep['offer_ribbon'] ?? '';
    }

    // Get Ship Specification
    if (!empty($shipBuilder)) {
        $shipSpecifications = getFlexibleSection($shipBuilder, 'ship_specifications');
    }
    $shipDetails = [];
    if (!empty($shipSpecifications['ship_details'])) {
        foreach ($shipSpecifications['ship_details'] as $item) {
            $label = trim($item['label'] ?? '');
            $labelText = trim($item['label_text'] ?? '');
            if ($label || $labelText) {
                $shipDetails[] = [
                    'label' => $label,
                    'label_text' => $labelText,
                ];
            }
        }
    }

    // Get Hotel Star
    $hotelsRating = $hotels['rating'] ?? '';

    // Get Product Data
    $prodShortDesc = !empty($product['short_description']) ? $product['short_description'] : '';
    $prodDuration = !empty($product['duration']) ? $product['duration'] : '';
    $prodRegularPrice = !empty($product['regular_price']) ? $product['regular_price'] : '';
    $prodSalePrice = !empty($product['sale_price']) ? $product['sale_price'] : '';
    $prodTripLocations = !empty($product['trip_locations']) ? $product['trip_locations'] : '';
    $special_offer = !empty($product['special_offer']) ? $product['special_offer'] : '';

    // Get Hotel Data
    $hotel_one_liner = !empty($hotels['hotel_one_liner']) ? $hotels['hotel_one_liner'] : '';

    // FINAL RIBBON - Post ribbon > Theme ribbon fallback
    $final_ribbon_option = !empty($post_ribbon) ? $post_ribbon : $theme_offer_ribbon;

    $background_color = !empty($final_ribbon_option['background_color']) ? $final_ribbon_option['background_color'] : '';
    $text_color = !empty($final_ribbon_option['text_color']) ? $final_ribbon_option['text_color'] : '';
    $alignment = !empty($final_ribbon_option['alignment']) ? $final_ribbon_option['alignment'] : '';



    if (empty($btntitle) && !empty($button_text)) {
        $btntitle = $button_text;
    }

    $printable = '';
    $printable .= '<div class="blogItem" ' . ($boxColor ? 'style="background: ' . $boxColor . ';"' : '') . '>';
        if ($blogUrl) {
            $printable .= '<a href="' . esc_url($blogUrl) . '" class="fullAnchor" aria-label="'.$blogTitle.'"></a>';
        }

        $printable .= '<div class="blogImg">';
            if ($special_offer) {

                $printable .= '<div class="specialOffer ' . esc_attr($alignment) . '" style="background: ' . esc_attr($background_color) . ';">';
                $printable .= '<span style="color: ' . esc_attr($text_color) . ';">' . esc_html($special_offer) . '</span>';
                $printable .= '</div>';
            }
            // var_dump($blogImgID);
            if ($is_post != true) {
                $printable .= getFocalImage($rep['image'], 'image', $pageIndex, $repKey);
            } else {
                if($listingImg && ($post_type == 'products' || $post_type == 'ship_information' || $post_type == 'hotel_information') ){
                    $printable .= getFocalImage($listingImg, 'listing_image', 'single_module');
                }else{
                    $printable .= wp_get_attachment_image($blogImgID, 'full', false, ['loading' => 'lazy']);
                }
                // $printable .= wp_get_attachment_image($blogImgID, 'full', false, ['loading' => 'lazy']);
            }
        $printable .= '</div>'; #blogImg
        $printable .= '<div class="blogCont '.($boxColor ? 'boxColor' : '').'" ' . ($boxColor ? 'style=" padding: 20px;"' : '') . '>';
            $printable .= '<h3 class="title" ' . ($titleColor ? 'style="color: ' . esc_attr($titleColor) . ';"' : '') . '>' . esc_html($blogTitle) . '</h3>';
            if ($hotel_one_liner && $post_type == 'hotel_information') {
                $printable .= '<p class="tripLocation" style="text-transform: capitalize;">' . esc_html($hotel_one_liner) . '</p>';
            }

            if ($hotelsRating && $post_type == 'hotel_information') {
                $printable .= '<div class="starWrapper">';
                for ($i = 0; $i < $hotelsRating; $i++) {
                    $printable .= '<i class="fa-solid fa-star"></i>';
                }
                $printable .= '</div>'; #starWrapper
            }

            if ($select_auhtor || $date) {
                $printable .= '<div class="postAuthorDate">';
                $printable .= getMemberFromSpecialists($select_auhtor, $teamMembersOption, $date);
                $printable .= '</div>'; #postAuthorDate    
            }

            if ($prodTripLocations && $post_type == 'products') {
                $printable .= '<p class="tripLocation">' . esc_html($prodTripLocations) . '</p>';
            }
            if ($prodDuration && $post_type == 'products') {
                $printable .= regularSalePrice(perNight: true, duration: $prodDuration, regularPrice: $prodRegularPrice, salePrice: $prodSalePrice);
            }
            if ($prodShortDesc && $post_type == 'products') {
                $prodShortDesc = wp_trim_words(wp_strip_all_tags($prodShortDesc), 20, '...');
                $printable .= '<p>' . wp_kses_post($prodShortDesc) . '</p>';
            } else {
                if($post_type != 'ship_information'){
                    if ($staticBlogCont) {
                        $printable .= wp_kses_post($staticBlogCont);
                    } else {
                        $content_trimmed = wp_trim_words(wp_strip_all_tags($blogCont), 20, '...');
                        $printable .= '<p>' . esc_html($content_trimmed) . '</p>';
                    }    
                }
                
            }

            // Ship Data Show

            if (!empty($shipDetails) && $post_type == 'ship_information') {
                $printable .= '<ul>';
                foreach ($shipDetails as $shipItems) {
                    $printable .= '<li>';
                    if (!empty($shipItems['label'])) {
                        $printable .= '<span class="label">' . esc_html($shipItems['label']) . '</span>';
                    }
                    if (!empty($shipItems['label_text'])) {
                        $printable .= '<span class="labelText">' . esc_html($shipItems['label_text']) . '</span>';
                    }
                    $printable .= '</li>';
                }
                $printable .= '</ul>';
            }

            if (!empty($blogUrl)) {
                $printable .= '<a href="' . esc_url($blogUrl) . '" ' . ($btntarget ? 'target="' . esc_attr($btntarget) . '"' : '') . ' class="btn" aria-label="' . esc_attr($blogTitle) . '">'
                    // . ($post_type == 'ship_information' ? $blogTitle : (!empty($btntitle) ? $btntitle : 'Discover More') ) . 
                    . (!empty($btntitle) ? $btntitle : ($post_type == 'ship_information' ? $blogTitle : 'Discover More')) .
                    '</a>';
            }
        $printable .= '</div>'; #blogCont
    $printable .= '</div>'; #blogItem
    return $printable;
}

// WYSIWYG Editor Read More Read Less
function WysiwygReadMoreLess($wysiwyg, $class = '')
{
    $printable = '';
    if ($wysiwyg) {
        $parts = explode('<!--more-->', $wysiwyg);
        $printable .= '<div class="contentWrapper ' . esc_attr($class) . '">';
        if (count($parts) > 1) {
            $printable .= '<div class="wysiwygShortContent">' . wpautop($parts[0]) . '</div>';
            $printable .= '<div class="wysiwygFullContent" style="display:none;">' . wpautop($parts[1]) . '</div>';
            $printable .= '<div class="wysiwygToggleWrap" >';
            $printable .= '<a href="#" class="wysiwygReadMore">
                                Read More
                                <svg xmlns="//www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                                    <path d="M5.6562 6.86332C5.85692 6.21754 6.27895 5.48164 6.5826 4.89093C6.79876 4.47542 7.04581 4.08495 7.32887 3.71951C8.03912 2.79839 8.95522 2.04247 10 1.51684V0C10 0 9.98971 0 9.98456 0C8.43541 0.640776 7.09727 1.69706 6.11426 3.02366C5.8878 3.32903 5.65105 3.6344 5.48121 3.96981C5.48121 3.96981 5.18793 4.64707 5 4.65684C4.81207 4.66662 4.51879 4.01987 4.51879 4.01987C4.32836 3.67445 4.11734 3.34405 3.88574 3.02867C2.90273 1.70206 1.56459 0.645782 0.01544 0.00500555C0.01544 0.00500555 0.00514698 0.00500555 0 0.00500555V1.52184C1.04992 2.04748 1.96089 2.8084 2.67113 3.72451C2.95419 4.08996 3.20123 4.48544 3.4174 4.89593C3.72105 5.48164 4.10705 6.04232 4.30777 6.69311C4.70921 7.87454 4.97684 7.99969 4.99228 7.99969C5.00772 7.99969 5.23932 8.04976 5.64076 6.86832L5.6562 6.86332Z" fill="#bb5b3e"/>
                                </svg>
                                </a>';
            $printable .= '<a href="#" class="wysiwygReadLess" style="display:none;">
                                Read Less
                                <svg xmlns="//www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                                    <path d="M5.6562 6.86332C5.85692 6.21754 6.27895 5.48164 6.5826 4.89093C6.79876 4.47542 7.04581 4.08495 7.32887 3.71951C8.03912 2.79839 8.95522 2.04247 10 1.51684V0C10 0 9.98971 0 9.98456 0C8.43541 0.640776 7.09727 1.69706 6.11426 3.02366C5.8878 3.32903 5.65105 3.6344 5.48121 3.96981C5.48121 3.96981 5.18793 4.64707 5 4.65684C4.81207 4.66662 4.51879 4.01987 4.51879 4.01987C4.32836 3.67445 4.11734 3.34405 3.88574 3.02867C2.90273 1.70206 1.56459 0.645782 0.01544 0.00500555C0.01544 0.00500555 0.00514698 0.00500555 0 0.00500555V1.52184C1.04992 2.04748 1.96089 2.8084 2.67113 3.72451C2.95419 4.08996 3.20123 4.48544 3.4174 4.89593C3.72105 5.48164 4.10705 6.04232 4.30777 6.69311C4.70921 7.87454 4.97684 7.99969 4.99228 7.99969C5.00772 7.99969 5.23932 8.04976 5.64076 6.86832L5.6562 6.86332Z" fill="#bb5b3e"/>
                                </svg>
                                </a>';
            $printable .= '</div>';
        } else {
            $printable .= wpautop($wysiwyg);
        }
        $printable .= '</div>'; #contentWrapper
    }
    return $printable;
}

// Customized Trip From ThemeOption
function customizedTripFromOption()
{

    $customized_title = get_field('customized_title', 'option');
    $customized_sub_title = get_field('customized_sub_title', 'option');
    $customized_image = get_field('customized_image', 'option');
    $customized_link = get_field('customized_link', 'option');
    $customized_content = get_field('customized_content', 'option');

    $printable = '';
    $printable .= '<div class="custTripWrapper">';
    if ($customized_image || $customized_title || $customized_sub_title) {
        $printable .= '<div class="custTripImg">';
        $printable .= wp_get_attachment_image($customized_image, 'full', false, ['loading' => 'lazy']);
        $printable .= '<div class="overlayData">';
        if ($customized_title) {
            $printable .= '<span class="title">' . esc_html($customized_title) . '</span>';
        }
        if ($customized_sub_title) {
            $printable .= '<p class="subtitle">' . esc_html($customized_sub_title) . '</p>';
        }
        $printable .= '</div>'; #overlayData
        $printable .= '</div>'; #custTripImg
    }
    $printable .= '<div class="custTripCont">';
    if ($customized_content) {
        $printable .= '<div class="wysiwygContent">';
        $printable .= wp_kses_post($customized_content);
        $printable .= '</div>'; #wysiwygContent
    }
    if ($customized_link) {
        $link_url = $customized_link['url'];
        $link_title = $customized_link['title'];
        $link_target = $customized_link['target'] ? $customized_link['target'] : '_self';
        $printable .= '<div class="custButton">';
        $printable .= '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" class="btn" role="button" aria-label="' . esc_attr($link_title) . '">' . esc_html($link_title) . '</a>';
        $printable .= '</div>'; #custButton
    }
    $printable .= '</div>'; #custTripCont
    $printable .= '</div>'; #custTripWrapper
    return $printable;
}

function customComments($rep)
{

    $comments = $rep['comments'] ?: '';
    $name = $rep['name'] ?: '';
    $location = $rep['location'] ?: '';

    $printable = '';
    $printable .= '<div class="CommentsBox">';
    $printable .= '<div class="CommentsText">';
    $printable .= wpautop($comments);
    $printable .= '</div>'; #CommentsText
    $printable .= '<div class="CommentsName">';
    if (!empty($name)) {
        $printable .= '<h3 class="name">' . $name . '</h3>';
    }
    if ($location) {
        $printable .= '<span class="location">' . $location . '</span>';
    }
    $printable .= '</div>'; #CommentsText
    $printable .= '</div>'; #CommentsBox
    return $printable;
}

// nofollow Link
function add_nofollow_to_custom_links($value, $post_id, $field)
{
    if ($field['name'] === 'custom_link') {
        if (!empty($value['url'])) {
            $value['url'] = $value['url'];
            $value['attributes'] = 'rel="nofollow"';
        }
    }
    return $value;
}
add_filter('acf/load_value', 'add_nofollow_to_custom_links', 10, 3);

// Pagination Code (AJAX Friendly)
function pagination($paged = '', $max_page = '')
{
    if (! $paged) $paged = 1;

    if (! $max_page) {
        global $wp_query;
        $max_page = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
    }

    if ($max_page <= 1) {
        return;
    }

    echo '<ul class="cusPagination page-numbers">';

    // Previous page link
    if ($paged > 1) {
        echo '<li><a class="prev page-numbers" href="#" data-page="' . ($paged - 1) . '">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none"><path d="M1.19002 4.3438C1.86611 4.14308 2.63653 3.72105 3.25497 3.4174C3.68997 3.20124 4.09877 2.95419 4.48136 2.67113C5.4457 1.96089 6.23708 1.04478 6.78739 0H8.3754C8.3754 0 8.3754 0.0102934 8.3754 0.01544C7.70456 1.56459 6.59871 2.90273 5.20985 3.88574C4.89015 4.1122 4.57045 4.34894 4.21931 4.51879H15V5.48121H4.1669C4.52853 5.67164 4.87443 5.88266 5.20461 6.11426C6.59347 7.09727 7.69932 8.43541 8.37016 9.98456C8.37016 9.98456 8.37016 9.99485 8.37016 10H6.78215C6.23184 8.95008 5.43521 8.03911 4.47612 7.32887C4.09352 7.04581 3.67949 6.79876 3.24973 6.5826C2.63653 6.27895 2.04954 5.89295 1.36822 5.69223C0.131346 5.29079 0.000320435 5.02316 0.000320435 5.00772C0.000320435 4.99228 -0.0520887 4.76068 1.18478 4.35924L1.19002 4.3438Z" fill="#FCF4E7"></path></svg>
        </a></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $max_page; $i++) {
        if ($i == $paged) {
            echo '<li><span class="page-numbers current">' . $i . '</span></li>';
        } else {
            echo '<li><a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a></li>';
        }
    }

    // Next page link
    if ($paged < $max_page) {
        echo '<li><a class="next page-numbers" href="#" data-page="' . ($paged + 1) . '">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none"><path d="M13.81 4.3438C13.1339 4.14308 12.3635 3.72105 11.745 3.4174C11.31 3.20124 10.9012 2.95419 10.5186 2.67113C9.5543 1.96089 8.76292 1.04478 8.21261 0H6.6246C6.6246 0 6.6246 0.0102934 6.6246 0.01544C7.29544 1.56459 8.40129 2.90273 9.79015 3.88574C10.1098 4.1122 10.4295 4.34894 10.7807 4.51879H0V5.48121H10.8331C10.4715 5.67164 10.1256 5.88266 9.79539 6.11426C8.40653 7.09727 7.30068 8.43541 6.62984 9.98456C6.62984 9.98456 6.62984 9.99485 6.62984 10H8.21785C8.76816 8.95008 9.56479 8.03911 10.5239 7.32887C10.9065 7.04581 11.3205 6.79876 11.7503 6.5826C12.3635 6.27895 12.9505 5.89295 13.6318 5.69223C14.8687 5.29079 14.9997 5.02316 14.9997 5.00772C14.9997 4.99228 15.0521 4.76068 13.8152 4.35924L13.81 4.3438Z" fill="#FCF4E7"></path></svg>
        </a></li>';
    }

    echo '</ul>';
}

// Jumplink / Quick Link Global
function jumplinksCode($jump, $jumplink_layout = '', $jumplink_title = '')
{
    $jumplinks = $jump;

    $validLinks = array_filter($jumplinks, function ($item) {
        return !empty($item['label']) && (!empty($item['jumplink_section_id']) || !empty($item['link_another_page']));
    });

    if (empty($validLinks)) {
        return '';
    }

    $printable = '';
    // if ( $jumplinks[0]['label'] && ( $jumplinks[0]['jumplink_section_id'] || $jumplinks[0]['link_another_page'] ) ){
    $printable .= '<section id="jumplinks-sec" class="jumplinks ' . esc_attr($jumplink_layout) . ' full-section">';
    $printable .= '<div class="container">';
    $printable .= '<div class="mobJumplink" style="display: none;">
                                <span>' . ($jumplink_layout == 'default' ? 'Jumplinks' : ($jumplink_title ? $jumplink_title : 'Article Contents')) . '</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                                <path d="M5.6562 6.86332C5.85692 6.21754 6.27895 5.48164 6.5826 4.89093C6.79876 4.47542 7.04581 4.08495 7.32887 3.71951C8.03912 2.79839 8.95522 2.04247 10 1.51684V0C10 0 9.98971 0 9.98456 0C8.43541 0.640776 7.09727 1.69706 6.11426 3.02366C5.8878 3.32903 5.65105 3.6344 5.48121 3.96981C5.48121 3.96981 5.18793 4.64707 5 4.65684C4.81207 4.66662 4.51879 4.01987 4.51879 4.01987C4.32836 3.67445 4.11734 3.34405 3.88574 3.02867C2.90273 1.70206 1.56459 0.645782 0.01544 0.00500555C0.01544 0.00500555 0.00514698 0.00500555 0 0.00500555V1.52184C1.04992 2.04748 1.96089 2.8084 2.67113 3.72451C2.95419 4.08996 3.20123 4.48544 3.4174 4.89593C3.72105 5.48164 4.10705 6.04232 4.30777 6.69311C4.70921 7.87454 4.97684 7.99969 4.99228 7.99969C5.00772 7.99969 5.23932 8.04976 5.64076 6.86832L5.6562 6.86332Z" fill="#ffffff"/>
                                </svg>
                            </div>';
    $printable .= '<ul>';

    foreach ($jumplinks as $key => $jump) {
        $label = $jump['label'] ?: '';
        $jumplink_id = $jump['jumplink_section_id'] ?: '';
        $link_page = $jump['link_another_page'] ?: '';

        if ($label && ($jumplink_id || $link_page)) {
            $printable .= '<li>';
            if ($link_page) {
                $link_url = $link_page['url'];
                $link_title = $link_page['title'];
                $link_target = $link_page['target'] ? $link_page['target'] : '_self';
                $printable .= '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" ' . ($link_target == '_blank' ? 'rel="nofollow"' : '') . '>' . ($label ? esc_html($label) : esc_html($link_title)) . '</a>';
            } else {
                $printable .= '<a href="#' . esc_attr($jumplink_id) . '">' . esc_html($label) . '</a>';
            }

            $printable .= '</li>';
        }
    }
    $printable .= '</ul>';
    $printable .= '</div>';
    $printable .= '</section>';
    // }
    return $printable;
}

// ALT Required

add_filter('acf/validate_value/type=image', function ($valid, $value, $field, $input) {
    if (!$valid) {
        return $valid;
    }

    // Value empty ho to skip karo (required field ACF khud handle karta hai)
    if (empty($value)) {
        return $valid;
    }

    // Value array bhi ho sakta hai (ACF return format ke mutabiq)
    $attachment_id = is_array($value) ? ($value['id'] ?? 0) : $value;

    if (!$attachment_id || !is_numeric($attachment_id)) {
        return $valid;
    }

    $alt = get_post_meta((int) $attachment_id, '_wp_attachment_image_alt', true);

    // false, null, ya whitespace-only — sab invalid
    if ($alt === false || $alt === null || trim((string) $alt) === '') {
        return 'The page will not update until you add an ALT text to this image.';
    }

    return $valid;
}, 10, 4);


// Add rel="nofollow" to ANY <a target="_blank"> on the whole site
add_action('template_redirect', function () {
    ob_start(function ($buffer) {

        return preg_replace_callback(
            '/<a\s+([^>]*target="_blank"[^>]*)>/i',
            function ($m) {
                $tag = $m[0];

                // If rel exists: append nofollow
                if (preg_match('/rel="([^"]*)"/i', $tag, $relMatch)) {
                    if (stripos($relMatch[1], 'nofollow') === false) {
                        $newRel = trim($relMatch[1] . ' nofollow');
                        return preg_replace('/rel="[^"]*"/i', 'rel="' . $newRel . '"', $tag);
                    }
                    return $tag;
                }

                // If rel doesn't exist: add it
                return str_replace('<a', '<a rel="nofollow"', $tag);
            },
            $buffer
        );
    });
});

//add_filter('wp_handle_upload_prefilter', 'uh_custom_upload_error');
function uh_custom_upload_error($file)
{

    $limit = 2 * 1024 * 1024; // 2 MB

    if ($file['size'] > $limit) {

        // Custom clear message for your site
        $file['error'] = 'Upload Failed: Only images under 2MB are allowed. Your file is ' . size_format($file['size'], 2) . '.';
    }

    return $file;
}

function wordpressAdminCss()
{
    wp_add_inline_style(
        'wp-admin',
        '.acf-field.dnone { display: none }
        .post-type-products .acf-field.proData{ display: block; }
        .post-type-ship_information .acf-field.shipData{ display: block; }
        .post-type-hotel_information .acf-field.hotelData{ display: block; }
        .acf-field.repeatorFifty table.acf-table tbody {display:flex !important;flex-wrap:wrap !important;}
        .acf-field.repeatorFifty table.acf-table tbody tr.acf-row{width:50% !important;}
        .acf-field.repeatorFifty table.acf-table tbody tr.acf-row td.acf-fields{width:90% !important;} 
        .acf-field.disable .acf-input input { pointer-events: none; opacity: 0.6; cursor: not-allowed; }
        .acf-field[data-name="gallery_images"] .acf-input .acf-repeater table.acf-table td.acf-fields .acf-field.acf-field-image button.custom-cropper-trigger { display: none;}
        '
    );
}
add_action('admin_enqueue_scripts', 'wordpressAdminCss');

// Add Class on Backend Body
function add_post_id_to_admin_body_class($classes)
{
    if (is_admin() && function_exists('get_current_screen')) {
        $screen = get_current_screen();
        // Check if we are on the edit page for a post, page, or custom post type
        if (($screen->base == 'post' || $screen->base == 'page') && isset($_GET['post'])) {
            $post_id = $_GET['post'];
            $classes .= ' post-id-' . $post_id;
        }
    }
    return $classes;
}
add_filter('admin_body_class', 'add_post_id_to_admin_body_class');


// get member from Specialists
function getMemberFromSpecialists($select_author, $teamMembersOption, $date = '', $profileDesc = false, $profileLink = false)
{
    $printable = '';
    if ($select_author && $teamMembersOption) {
        $selected_name = $select_author;
        $member_data = null;

        foreach ($teamMembersOption as $team_member_row) {
            if ($team_member_row['name'] === $selected_name) {
                $member_data = $team_member_row;
                break;
            }
        }

        if ($member_data) {
            $profile_image = $member_data['profile_image'];
            $name = $member_data['name'];
            $designation = $member_data['designation'];
            $profile_description = $member_data['profile_description'];
            $page_link = $member_data['page_link'];

            $printable .= '<div class="teamMember" aria-label="Team Members">';
            if ($profile_image) {
                $printable .= '<div class="teamImg">';
                $printable .= wp_get_attachment_image(
                    $profile_image,
                    'full',
                    false,
                    [
                        'loading' => 'lazy',
                    ]
                );
                $printable .= '</div>'; #teamImg
            }
            if ($name || $designation || $date || $profileDesc || $profileLink) {
                $printable .= '<div class="teamDetails">';
                if (!empty($name)) {
                    $printable .= '<span>' . esc_html($name) . '</span>';
                }
                if (!empty($designation)) {
                    $printable .= '<p class="designation">' . esc_html($designation) . '</p>';
                }
                if (!empty($date)) {
                    $printable .= '<p class="postDate">' . esc_html($date) . '</p>';
                }
                if ($profileDesc && $profile_description) {
                    $printable .= '<div class="profileDesc">' . wp_trim_words($profile_description, 20, '...') . '</div>';
                }
                if ($profileLink && $page_link) {
                    $printable .= '<a href="' . esc_url($page_link) . '" target="_self" class="btn" role="button" aria-label="View Profile">View Profile</a>';
                }
                $printable .= '</div>'; #teamDetails
            }
            $printable .= '</div>'; #teamMember

        }
    }
    return $printable;
}

// preview size to acf Image
add_filter('acf/load_field/type=image', function ($field) {
    // Set default preview size to 'thumbnail' (150x150)
    $field['preview_size'] = 'thumbnail';
    return $field;
});

// Generate Shortcode (Buttons)
add_filter('acf/update_value/name=button_rand_key', function ($value, $post_id, $field) {
    if (!empty($value)) return $value;
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 5);
}, 10, 3);

// Shortcode auto-generate after save
add_action('acf/save_post', 'generate_button_shortcodes', 20);
function generate_button_shortcodes($post_id)
{

    if ($post_id !== 'options') return;
    if (!have_rows('buttons', 'option')) return;

    while (have_rows('buttons', 'option')) {
        the_row();
        $randomKey = get_sub_field('button_rand_key', 'option');
        if ($randomKey) {
            $shortcode = '[buttons id="' . $randomKey . '"]';
            update_sub_field('button_shortcode', $shortcode);
        }
    }
}

// Shortcode output
function buttonRepeatorShortcodes($atts)
{

    $atts = shortcode_atts(array(
        'id' => ''
    ), $atts);

    if (!$atts['id']) return '';

    $buttons = get_field('buttons', 'option');
    if (!$buttons) return '';

    foreach ($buttons as $btn) {
        if ($btn['button_rand_key'] === $atts['id']) {

            $btnLink = $btn['link'];

            $link_url = $btnLink['url'];
            $link_title = $btnLink['title'];
            $link_target = $btnLink['target'] ? $btnLink['target'] : '_self';

            return '<div class="ctaButton">
                <a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" class="btn" role="button" aria-label="' . esc_attr($link_title) . '">' . esc_html($link_title) . '</a>
            </div>';
        }
    }

    return '';
}
add_shortcode('buttons', 'buttonRepeatorShortcodes');

// FocalImageFunction
function getFocalImage($imageField, $imageFieldName = '', $pageIndex = '', $repeaterKey = 0, $styleAttr = '', $loading = '', $fetchpriority = '')
{

    if (!$imageField) return '';

    $focalUrl = cca_get_focal_crop_url(
        $imageField,
        $imageFieldName,
        $pageIndex, // This index comes from page.php.
        $repeaterKey, // This index comes from acf repeator.
        get_the_ID()
    );

    $dynamicImgUrl = $focalUrl ?: wp_get_attachment_image_url($imageField, 'full');
    $imgMeta = get_post_meta($imageField, '_wp_attachment_image_alt', true);

    return '<img src="' . esc_url($dynamicImgUrl) . '" alt="' . esc_attr($imgMeta) . '" ' . ($styleAttr ? 'style="' . esc_attr($styleAttr) . '"' : '') . ' '.($loading ? 'loading="'.$loading.'"' : '').' '.($fetchpriority ? 'fetchpriority="'.$fetchpriority.'"' : '').'>';
}

function getFocalImageUrl($imageField, $imageFieldName = '', $pageIndex = '', $repeaterKey = 0,)
{
    if (!$imageField) return '';

    $focalUrl = cca_get_focal_crop_url(
        $imageField,
        $imageFieldName,
        $pageIndex, // This index comes from page.php.
        $repeaterKey, // This index comes from acf repeator.
        get_the_ID()
    );

    $dynamicImgUrl = $focalUrl ?: wp_get_attachment_image_url($imageField, 'full');
    // $imgMeta = get_post_meta($imageField, '_wp_attachment_image_alt', true);

    // return '<img src="'.esc_url($dynamicImgUrl).'" alt="'.esc_attr($imgMeta).'" '.($styleAttr ? 'style="'.esc_attr($styleAttr).'"' : '').'>';
    return  $dynamicImgUrl;
}


// Hotel Banner Repeater Validation: Block save if exactly 2 rows for hotel_information
add_filter('acf/validate_value/name=banner_slider', function ($valid, $value, $field, $input) {

    // If already invalid, return previous message
    if (!$valid) {
        return $valid;
    }

    // Make sure $value is an array of repeater rows
    if (!is_array($value)) {
        return $valid;
    }

    // Count rows
    $row_count = count($value);

    // Get current post ID from $_POST (works for existing posts)
    $post_id = isset($_POST['post_ID']) ? intval($_POST['post_ID']) : 0;

    // Get post type
    $post_type = $post_id ? get_post_type($post_id) : false;

    // Only validate for hotel_information
    if ($post_type === 'hotel_information') {

        // Exactly 2 rows are NOT allowed
        if ($row_count === 2) {
            return 'There are only two options: a single image or multiple images. For the banner to work, at least three images are required. If only two images are added, the page will not update.';
        }
    }

    return $valid;
}, 10, 4);

// add_filter( 'acf/field_group/disable_field_settings_tabs', '__return_true' );

function regularSalePrice($perNight = false, $duration = '', $regularPrice = '', $salePrice = '', $class = '')
{
    $printable = '';

    if (!$regularPrice && !$salePrice) return '';

    if ($perNight == true) {
        $printable .= '<p class="perNight proDuration' . esc_attr($class) . '">' . esc_html($duration) . ' From 
        ' . ($regularPrice ?
            '<span class="regPrice  dyn-price ' . ($salePrice ? 'salePriceActive' : '') . '" data-price="' . $regularPrice . '">' . $regularPrice . 'pp</span>' : '') . '
        ' . ($salePrice ?
            '<span class="salePrice dyn-price" data-price="' . $salePrice . '">' . $salePrice . 'pp</span>' : '') . ' </p>';
    } else {
        $printable .=
            '<p class="proDuration ' . esc_attr($class) . '"> 
            ' . ($regularPrice ?
                '<span class="regPrice  dyn-price ' . ($salePrice ? 'salePriceActive' : '') . '" data-price="' . $regularPrice . '">' . $regularPrice . 'pp</span>' : '') . '
        ' . ($salePrice ?
                '<span class="salePrice dyn-price" data-price="' . $salePrice . '">' . $salePrice . 'pp</span>' : '') . '
        </p>';
    }

    return $printable;
}


// Phone Number Shortcode
add_shortcode('getPhoneNumber', 'getPhoneNumberCode');
function getPhoneNumberCode()
{
    ob_start();

    $au_number = get_field('au_number', 'option') ?? '';
    $gb_number = get_field('gb_number', 'option') ?? '';
    $us_number = get_field('us_number', 'option') ?? '';

    if ($au_number || $gb_number || $us_number) {
        echo '<div class="callWrap numbers-switcher">';
            echo '<span>T: </span>';
            echo '<a class="cc-phone phone call_btn" rel="nofollow" href="tel:'.esc_attr($us_number).'">'.esc_html($us_number).'</a>';
        echo '</div>'; #callWrap
    }
    return '' . ob_get_clean();
}

add_filter( 'gform_disable_css', '__return_true' );

// Wordpress admin Css
function kvCustomAdminCss() {
    echo '<style>
        div#major-publishing-actions {flex-wrap: wrap !important;}
    </style>';
}
add_action( 'admin_head', 'kvCustomAdminCss' );