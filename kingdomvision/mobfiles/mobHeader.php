<?php

$au_number = get_field('au_number' , 'option');
$gb_number = get_field('gb_number' , 'option');
$us_number = get_field('us_number' , 'option');
// $currency = cf_curr_currency();
$logo = get_field('logo_image', 'option');

// 
$menu_button = get_field('menu_button' , 'option');
$menu_content = get_field('menu_content' , 'option');

// $header_type = get_field('header_type' , get_queried_object());

$objectID = get_queried_object_id();
$headerLayout = '';

// Logic Theme Option & Post

$postHeader = get_field('header_type', $objectID);
if ($postHeader == '' || $postHeader == 'select') {
	$headerLayout = get_field('header_type', 'option');
}else{
	$headerLayout = get_field('header_type', $objectID);
}

// Logic Post & Empty Slider
$headerResult = '';
if( $postHeader == 'transparent' ){
	$headerResult = 'transparent';
}elseif(empty($banner_slider)){
	$headerResult = 'default';
}elseif ($postHeader === 'default') {
	$headerResult = 'default';
}else{
	$headerResult = $headerLayout;
}

echo '<div class="mobHeader full-section '.esc_attr($headerResult).'" style="display: none;">';
    echo '<div class="container">';
        echo '<div class="mobMenu">';
            echo '<a href="javascript:;" class="clickMenu" aria-label="humburger">';
                echo '<div class="humburgerSVG">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="39" height="26" viewBox="0 0 39 26" fill="none">
                    <line y1="0.5" x2="38.1845" y2="0.5" stroke="white"/>
                    <line y1="12.5" x2="38.1845" y2="12.5" stroke="white"/>
                    <line y1="25.5" x2="38.1845" y2="25.5" stroke="white"/>
                    </svg>';
                echo '</div>'; #humburgerSVG
				echo '<div class="closeSVG" style="display: none;">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                        <path d="M0.353638 25.3536L25.3536 0.353554" stroke="white"></path>
                        <path d="M0.353594 0.353558L25.3536 25.3536" stroke="white"></path>
                    </svg>';
                echo '</div>'; #closeSVG
            echo '</a>';
        echo '</div>'; #mobMenu
        if($logo){
            echo '<div class="logoWrap">';
                echo '<a href="' . home_url( '/' ) . '">';
                    if($logo) {
                        echo '<img src="' . $logo . '" width="265" height="50" alt="Unforgettable Croatia" fetchpriority="high"/>';
                    } else {
                        echo '<img src="' . get_stylesheet_directory_uri() . '/images/logo.png" width="265" height="50" alt="Unforgettable Croatia" />';
                    }
                echo '</a>';
            echo '</div>'; #logoWrap
        }

        echo '<div class="mobile_phone">';
            echo '<a class="cc-phone phone call_btn" rel="nofollow" href="tel:' . $us_number . '" aria-label="'.$us_number.'">';
            	echo '<i class="fa-brand fa-phone"></i>';
            echo '</a>';
            //hidden data
            echo '<ul style="display:none;">
                <li data-curr="USD" data-phone="' . $us_number . '" data-link="' . $us_number . '"></li>
                <li data-curr="AUD" data-phone="' . $au_number . '" data-link="' . $au_number . '"></li>
                <li data-curr="GBP" data-phone="' . $gb_number . '" data-link="' . $gb_number . '"></li>
                <li data-curr="EUR" data-phone="' . $us_number . '" data-link="' . $us_number . '"></li>
            </ul>';
        echo '</div>'; //mobile_phone

        if(has_nav_menu('main-menu')){
            echo '<nav class="navigation-wrapper" role="navigation">';
                wp_nav_menu([
                    'theme_location' => 'main-menu',
                    'container_class' => 'mobile-main-menu'
                ]);
                echo '<div class="currency-mobile">';
					echo '<div class="current-status cc-current-mobile">';
						switch ($currency) {
							case 'GBP':
								$sign = '&pound;';
								break;
							case 'EUR':
								$sign = '&euro;';
								break;
							case 'AUD':
								$sign = '$';
								break;
							default:
								$sign = '$';
						}
						echo $sign . ' ' . esc_html($currency);
					echo '</div>';

					echo '<ul class="auto_switcher right">';
						$currencies = [
							'USD' => 'usa-flag.jpg',
							'AUD' => 'aus-flag.jpg',
							'GBP' => 'gbp-flag.jpg',
							'EUR' => 'eur-flag.webp',
						];
						foreach ($currencies as $code => $flag) {
							$is_current = (cf_curr_currency() === $code) ? 'current' : '';
							$flag_url = esc_url(get_template_directory_uri() . '/images/' . $flag);
							switch ($code) {
								case 'GBP':
									$sign = '&pound;';
									break;
								case 'EUR':
									$sign = '&euro;';
									break;
								case 'AUD':
									$sign = '$';
									break;
								default:
									$sign = '$';
							}

							printf(
								'<li><a data-value="%s" class="auto_switcher_link %s" href="javascript:;"> %s %s <img src="%s" alt="%s"></a></li>',
								esc_attr($code),
								esc_attr($is_current),
								$sign,
								esc_html($code),
								$flag_url,
								esc_html($code)
							);
						}
					echo '</ul>';
				echo '</div>';
				if($menu_content || $menu_button){
					echo '<div class="ContentInfo" role="complementary">';
						if($menu_content){
							echo '<div class="Content">';
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
            echo '</nav>'; #navigation-wrapper
        }
    echo '</div>'; #container
echo '</div>'; #mobHeader


?>

