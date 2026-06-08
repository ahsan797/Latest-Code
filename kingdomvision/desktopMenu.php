<?php
$menu_image = get_field('menu_image' , 'option');
$menu_button = get_field('menu_button' , 'option');
$menu_content = get_field('menu_content' , 'option');

$logo = get_field('logo_image', 'option');
$au_number = get_field('au_number' , 'option');
$gb_number = get_field('gb_number' , 'option');
$us_number = get_field('us_number' , 'option');
$currency = cf_curr_currency();

echo '<div class="menuWrapper full-section" style="display: none;">';
    echo '<div class="container">';

        echo '<div class="menuHeader">';
            echo '<div class="desktopMenu">';
                echo '<a href="javascript:;" class="clickMenu">';
                    echo '<div class="humburgerSVG">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                        <path d="M0.353638 25.3536L25.3536 0.353554" stroke="white"/>
                        <path d="M0.353594 0.353558L25.3536 25.3536" stroke="white"/>
                        </svg>';
                    echo '</div>'; #humburgerSVG
                echo '</a>';
            echo '</div>'; #desktopMenu
            echo '<div class="logoWrap">';
                echo '<a href="' . home_url( '/' ) . '">';
                    if($logo) {
                        echo '<img src="' . $logo . '" width="265" height="50" alt="Unforgettable Croatia" fetchpriority="high"/>';
                    } else {
                        echo '<img src="' . get_stylesheet_directory_uri() . '/images/logo.png" width="265" height="50" alt="Unforgettable Croatia" />';
                    }
                echo '</a>';
			echo '</div>'; #logoWrap
            if($au_number || $gb_number || $us_number){
                echo '<div class="callWrap">';
                    // if ($currency === 'USD' && !empty($us_number)) {
                    //     $us_number = preg_replace('/\s+/', '', $us_number);
                    //     printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));
                    // } elseif ($currency === 'AUD' && !empty($au_number)) {
                    //     $au_number = preg_replace('/\s+/', '', $au_number);
                    //     printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($au_number), esc_html($au_number));
                    // } elseif ($currency === 'GBP' && !empty($gb_number)) {
                    //     $gb_number = preg_replace('/\s+/', '', $gb_number);
                    //     printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($gb_number), esc_html($gb_number));
                    // } else {
                    //     $us_number = preg_replace('/\s+/', '', $us_number);
                    //     printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));            
                    // }

                    echo '<a class="cc-phone phone call_btn" rel="nofollow" href="tel:'.esc_attr($us_number).'">'.esc_html($us_number).'</a>';

                        //hidden data
                        echo '<ul style="display:none;">
                            <li data-curr="USD" data-phone="' . $us_number . '" data-link="' . $us_number . '"></li>
                            <li data-curr="AUD" data-phone="' . $au_number . '" data-link="' . $au_number . '"></li>
                            <li data-curr="GBP" data-phone="' . $gb_number . '" data-link="' . $gb_number . '"></li>
                            <li data-curr="EUR" data-phone="' . $us_number . '" data-link="' . $us_number . '"></li>
                        </ul>';
                echo '</div>'; #callWrap
            }
        echo '</div>'; #menuHeader

        echo '<div class="menuContentWrap">';
            echo '<div class="leftArea" role="navigation" aria-label="Main menu">';
                if (has_nav_menu('main-menu')){
                    wp_nav_menu([
                        'theme_location' => 'main-menu',
                        'container'      => false,
                        'container_class' => 'desktop-menu',
                        'fallback_cb'    => false,
                    ]);
                }
            echo '</div>'; #leftArea
            echo '<div class="rightArea">';
                if($menu_image){
                    echo '<div class="imgWrap">';
                        echo '<img src="'.esc_url($menu_image['url']).'" alt="'.esc_attr($menu_image['alt']).'">';
                    echo '</div>'; #imgWrap
                }
            echo '</div>'; #rightArea
        echo '</div>'; #menuContentWrap
        if($menu_content || $menu_button){
            echo '<div class="ContentInfo" role="complementary">';
                if($menu_content){
                    echo '<div class="Content">';
                    echo 'Speak to us today<br>';
                    if($au_number || $gb_number || $us_number){
                            if ($currency === 'USD' && !empty($us_number)) {
                                $us_number = preg_replace('/\s+/', '', $us_number);
                                printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));
                            } elseif ($currency === 'AUD' && !empty($au_number)) {
                                $au_number = preg_replace('/\s+/', '', $au_number);
                                printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($au_number), esc_html($au_number));
                            } elseif ($currency === 'GBP' && !empty($gb_number)) {
                                $gb_number = preg_replace('/\s+/', '', $gb_number);
                                printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($gb_number), esc_html($gb_number));
                            } else {
                                $us_number = preg_replace('/\s+/', '', $us_number);
                                printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));            
                            }
                                //hidden data
                                echo '<ul style="display:none;">
                                    <li data-curr="USD" data-phone="' . $us_number . '" data-link="' . $us_number . '"></li>
                                    <li data-curr="AUD" data-phone="' . $au_number . '" data-link="' . $au_number . '"></li>
                                    <li data-curr="GBP" data-phone="' . $gb_number . '" data-link="' . $gb_number . '"></li>
                                    <li data-curr="EUR" data-phone="' . $us_number . '" data-link="' . $us_number . '"></li>
                                </ul>';
                    }
                        echo $menu_content;
                    echo '</div>'; #Content
                }
                if($menu_button){
                    $link_url = $menu_button['url'];
                    $link_title = $menu_button['title'];
                    $link_target = $menu_button['target'] ? $menu_button['target'] : '_self';

                    echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" class="btn">'.esc_html( $link_title ).'</a>';
                }
            echo '</div>'; #ContentInfo
        }
    echo '</div>'; #container
echo '</div>'; #menuWrapper
?>