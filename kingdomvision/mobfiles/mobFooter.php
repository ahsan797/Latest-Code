<?php

$footer_menus = get_field('footer_menus', 'options');
$social_title = get_field('social_title', 'options');
$social_icons = get_field('social_icons', 'options');
$address_content = get_field('address_content', 'options');
$copyright_text = get_field('copyright_text', 'options');
$w_c_hide_show  = get_field('w_c_hide_show', 'options');
$website_credit  = get_field('website_credit', 'options');

$logo_image  = get_field('logo_image', 'options');


echo '<div class="mobFooter full-section" style="display: none;">';
    echo '<div class="container">';
        if($logo_image){
            echo '<div class="logoWrap">';
                echo '<a href="' . home_url( '/' ) . '">';
                    if($logo_image) {
                        echo '<img src="' . $logo_image . '" width="265" height="50" alt="Unforgettable Croatia"/>';
                    } else {
                        echo '<img src="' . get_stylesheet_directory_uri() . '/images/logo.png" width="265" height="50" alt="Unforgettable Croatia" />';
                    }
                echo '</a>';
            echo '</div>'; # logoWrap
        }
        if($social_icons){
            echo '<div class="socialWrapper">';
                echo '<ul class="social_icons">';
                    foreach($social_icons as $key => $icons){
                        $icon = $icons['icons'] ?: '' ;
                        $url = $icons['url'] ?: '' ;
                        
                        echo '<li>';
                            if($url){
                                echo '<a href="'.esc_url( $url ).'" target="_blank" aria-label="'.esc_url( $url ).'"><i class="'.$icon.'"></i></a>';
                            }else{
                                echo '<i class="'.$icon.'"></i>';
                            }
                        echo '</li>';
                        
                    }
                echo '</ul>'; #social_icons
            echo '</div>'; #socialWrapper
        }
        if($footer_menus){
            echo '<div class="footerMenuWrapper">';
                foreach($footer_menus as $key => $menus){
                    $footer_links = $menus['footer_links'] ?: '';
                    
                    // role="column'.esc_attr($key + 1).'"
                    echo '<div class="col column'.esc_attr($key + 1).'"  aria-labelledby="column'.esc_attr($key + 1).'">';
                        $ul_open = false;
                        if($footer_links){
                            echo '<div class="footer_links">';
                                foreach($footer_links as $key => $links){
                                    $main_title = $links['main_title'] ?: '';
                                    $link = $links['link'] ?: '';

                                    if($main_title){
                                        if ($ul_open) {
                                            echo '</ul>';
                                            $ul_open = false;
                                        }

                                        echo '<div class="'.esc_attr($main_title).' mobTitle">';
                                            echo '<span class="widget-title">'.esc_html($main_title).'</span>';
                                            echo '<span class="arrow"><svg width="8" height="10" viewBox="0 0 8 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.86332 5.6562C6.21754 5.85692 5.48164 6.27895 4.89093 6.5826C4.47542 6.79876 4.08495 7.04581 3.71951 7.32887C2.79839 8.03912 2.04247 8.95522 1.51684 10H0C0 10 0 9.98971 0 9.98456C0.640776 8.43541 1.69706 7.09727 3.02366 6.11426C3.32903 5.8878 3.6344 5.65105 3.96981 5.48121C3.96981 5.48121 4.64707 5.18793 4.65684 5C4.66662 4.81207 4.01987 4.51879 4.01987 4.51879C3.67445 4.32836 3.34405 4.11734 3.02867 3.88574C1.70206 2.90273 0.645782 1.56459 0.00500555 0.01544C0.00500555 0.01544 0.00500555 0.00514698 0.00500555 0H1.52184C2.04748 1.04992 2.8084 1.96089 3.72451 2.67113C4.08996 2.95419 4.48544 3.20123 4.89593 3.4174C5.48164 3.72105 6.04232 4.10705 6.69311 4.30777C7.87454 4.70921 7.99969 4.97684 7.99969 4.99228C7.99969 5.00772 8.04976 5.23932 6.86832 5.64076L6.86332 5.6562Z" fill="#FCF4E7"/>
                                                </svg></span>';
                                        echo '</div>';
                                        echo '<ul class="links" style="display: none;">';
                                        $ul_open = true;
                                    }
                                    if($link){
                                        $link_url = $link['url'];
                                        $link_title = $link['title'];
                                        $link_target = $link['target'] ? $link['target'] : '_self';
                                        echo '<li>';
                                            echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">'.esc_html( $link_title ).'</a>';
                                        echo '</li>';
                                    }
                                }
                                if ($ul_open) {
                                    echo '</ul>';
                                }
                            echo '</div>'; #footer_links
                        }
                    echo '</div>';
                }
            echo '</div>'; #footerMenuWrapper
        }
        if ($copyright_text || $website_credit) {
            echo '<div class="copyRight">';
                // role="copyRight"
                echo '<div class="copyRight-inner"  aria-labelledby="copyRight">';
                    if ($copyright_text) {
                        echo '<div class="leftSide">';
                            echo '<p>' . do_shortcode($copyright_text) . '</p>';
                        echo '</div>';
                    }
                    if ($website_credit && $w_c_hide_show == true) {
                        echo '<div class="rightSide">';
                            echo '<p>' . wp_kses_post($website_credit) . '</p>';
                        echo '</div>';
                    }
                echo '</div>'; // .copyRight-inner
            echo '</div>'; // .copyRight
        }
    echo '</div>';
echo '</div>'; #mobFooter

?>