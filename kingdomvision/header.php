<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
<?php wp_head(); ?>
</head>
	
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KVPHGTPS');</script>
<!-- End Google Tag Manager -->
	
<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KVPHGTPS"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager -->
<!--Main Wrapper-->
<div class="main-wrapper full-section" role="Main Wrapper" aria-labelledby="Main Wrapper">

<?php

	$menu_text = get_field('menu_text' , 'option');
	$search_icon = get_field('search_icon' , 'option');
	$planning_button = get_field('planning_button' , 'option');
	$au_number = get_field('au_number' , 'option');
	$gb_number = get_field('gb_number' , 'option');
	$us_number = get_field('us_number' , 'option');
	
	$currency = cf_curr_currency();

	// Header Default/Transparent With Banner Relation
	$banner_slider = get_field('banner_slider');

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

	echo '<header class="mainHeader full-section '.esc_attr($headerResult).'" >';
	// role="Header" aria-labelledby="Header"
		echo '<div class="container">';
			echo '<div class="menuWrap">';
				if($menu_text){
					echo '<div class="desktopMenu">';
						echo '<a href="javascript:;" class="clickMenu">';
							echo '<div class="humburgerSVG">';
								echo '<svg xmlns="http://www.w3.org/2000/svg" width="39" height="26" viewBox="0 0 39 26" fill="none">
								<line y1="0.5" x2="38.1845" y2="0.5" stroke="white"/>
								<line y1="12.5" x2="38.1845" y2="12.5" stroke="white"/>
								<line y1="25.5" x2="38.1845" y2="25.5" stroke="white"/>
								</svg>';
							echo '</div>'; #humburgerSVG
							echo '<span>'.esc_html($menu_text).'</span>';
						echo '</a>';
					echo '</div>'; #desktopMenu
				}

				echo '<div class="currency-switcher">';
					echo '<div class="current-status cc-current">';
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

					echo '<ul class="auto_switcher right ">';
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

			echo '</div>'; #menuWrap
			echo '<div class="logoWrap">';
				$logo = get_field('logo_image', 'option');
				echo '<a href="' . home_url( '/' ) . '">';
					if($logo) {
						echo '<img src="' . $logo . '" width="265" height="50" alt="Unforgettable Croatia" fetchpriority="high"/>';
					} else {
						echo '<img src="' . get_stylesheet_directory_uri() . '/images/logo.png" width="265" height="50" alt="Unforgettable Croatia" />';
					}
				echo '</a>';
			echo '</div>'; #logoWrap
			echo '<div class="btnWrap numbers-switcher">';
				if($search_icon){
					echo '<div class="seachIconWrapper">';
						echo wp_get_attachment_image($search_icon, 'full', false, ['loading' => 'eager']);
					echo '</div>'; #seachIconWrapper
				}
				if($au_number || $gb_number || $us_number){
					echo '<div class="callWrap">';
						// if ($currency === 'USD' && !empty($us_number)) {
						// 	$us_number = preg_replace('/\s+/', '', $us_number);
						// 	printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));
						// } elseif ($currency === 'AUD' && !empty($au_number)) {
						// 	$au_number = preg_replace('/\s+/', '', $au_number);
						// 	printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($au_number), esc_html($au_number));
						// } elseif ($currency === 'GBP' && !empty($gb_number)) {
						// 	$gb_number = preg_replace('/\s+/', '', $gb_number);
						// 	printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($gb_number), esc_html($gb_number));
						// } else {
						// 	$us_number = preg_replace('/\s+/', '', $us_number);
						// 	printf('<a class="cc-phone phone call_btn" rel="nofollow" href="tel:%s">%s</a>', esc_attr($us_number), esc_html($us_number));            
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
				if($planning_button){

					$link_url = $planning_button['url'];
					$link_title = $planning_button['title'];
					$link_target = $planning_button['target'] ? $planning_button['target'] : '_self';

					echo '<div class="planningWrap">';
						echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" class="enquiryNow" data-postTitle="'.esc_attr(get_the_title()).'" data-postID="'.esc_attr(get_the_ID()).'" role="button" aria-label="' . esc_attr($link_title) . '" >'.esc_html( $link_title ).'</a>';
					echo '</div>'; #planningWrap
				}
			echo '</div>'; #btnWrap
		echo '</div>';#container
	echo '</header>'; #mainHeader
	
	// MobileHeader
	require_once THEME_PATH .'/desktopMenu.php';
	include_once THEME_PATH . '/popSearchForm.php';
	require_once THEME_PATH . '/mobfiles/mobHeader.php';

?>