<?php

$page_id = get_queried_object_id();
$showhide_newsletter_section = get_field('showhide_newsletter_section', $page_id);

$footer_newsletter = get_field('footer_newsletter', 'options');

$newsletter_text_color = $footer_newsletter['newsletter_text_color'] ?: '';
$newsbg = $footer_newsletter['newsbg'] ?: '';
$background_image = $footer_newsletter['background_image'] ?: '';
$newsletter_bg_color = $footer_newsletter['newsletter_bg_color'] ?: '';

$heading_color = $footer_newsletter['heading_color'] ?: '';
$heading = $footer_newsletter['heading'] ?: '';
$sub_heading = $footer_newsletter['sub_heading'] ?: '';
$form_shortcode = $footer_newsletter['form_shortcode'] ?: '';

if($showhide_newsletter_section === null || $showhide_newsletter_section === false){
	$image_url = wp_get_attachment_image_url( $background_image, 'full' );
	if($newsbg == 'bgimage'){
		echo '<div class="footerNewsletter full-section '.$showhide_newsletter_section.' '.esc_attr($newsletter_text_color).'" id="newsletter" role="region" aria-label="Newsletter Signup"
		'.($image_url ? 'style="background: url('.$image_url.')no-repeat center/cover;"' : '').'>';
	} else {
		echo '<div class="footerNewsletter full-section '.$showhide_newsletter_section.' '.esc_attr($newsletter_text_color).'" id="newsletter" role="region" aria-label="Newsletter Signup"
		'.($newsletter_bg_color ? 'style="background-color: '. $newsletter_bg_color .';"' : '').'>';
	}
			echo '<div class="container">';
				if($heading || $sub_heading){
					echo '<div class="headingWrapper">';
						if($heading){
							echo '<h2 '.($heading_color ? 'style="color: '.$heading_color.';"' : '').'">'.esc_html($heading).'</h2>';
						}
						if($sub_heading){
							echo '<span>'.esc_html($sub_heading).'</span>';
						}
					echo '</div>'; #headingWrapper
				}
				if($form_shortcode){
					echo '<div class="formWrapper">';
						echo do_shortcode($form_shortcode);
					echo '</div>'; #formWrapper
				}
			echo '</div>';
		echo '</div>'; #footerNewsletter
}

$footer_menus = get_field('footer_menus', 'options');
$social_title = get_field('social_title', 'options');
$social_icons = get_field('social_icons', 'options');
$address_content = get_field('address_content', 'options');
$copyright_alignment = get_field('copyright_alignment', 'options');
$copyright_text = get_field('copyright_text', 'options');
$w_c_hide_show  = get_field('w_c_hide_show', 'options');
$website_credit  = get_field('website_credit', 'options');

$planning_button  = get_field('planning_button', 'options');

if($planning_button){
    $link_url = $planning_button['url'];
    $link_title = $planning_button['title'];
    $link_target = $planning_button['target'] ? $planning_button['target'] : '_self';

    echo '<div class="mobPlanningWrap">';
        echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">'.esc_html( $link_title ).'</a>';
    echo '</div>'; #mobPlanningWrap
}

// role="Footer" aria-label="Footer"
echo '<footer class="mainFooter full-section" >';
	echo '<div class="innerFooter">';
		echo '<div class="container">';
			if($footer_menus){
				echo '<div class="footer-columns" aria-label="Footer navigation">';
					foreach($footer_menus as $key => $menus){
						$footer_links = $menus['footer_links'] ?: '';
						
						// role="column'.esc_attr($key + 1).'"
						echo '<div class="col column'.esc_attr($key + 1).'" aria-labelledby="column'.esc_attr($key + 1).'">';
							if($footer_links){
								echo '<ul class="footer_links">';
									foreach($footer_links as $key => $links){
										$main_title = $links['main_title'] ?: '';
										$link = $links['link'] ?: '';

										if($main_title){
											echo '<li class="'.esc_attr($main_title).' ">';
												echo '<span class="widget-title">'.esc_html($main_title).'</span>';
											echo '</li>';
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
								echo '</ul>'; #footer_links
							}
						echo '</div>';
					}
					// role="Column4"
					echo '<div class="col column4"  aria-labelledby="Column4">';
						if($social_title){
							echo '<span class="widget-title">'.esc_html($social_title).'</span>';
						}
						if($social_icons){
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
						}
						if($address_content){
							echo '<div class="footerAddress">';
								echo wp_kses_post($address_content);
							echo '</div>'; #footerAddress
						}
					echo '</div>';
				echo '</div>'; // .footer-columns
			}
		echo '</div>'; // .container
	echo '</div>'; // .innerFooter

	if ($copyright_text || $website_credit) {
		echo '<div class="copyRight">';
			echo '<div class="container">';
			// role="copyRight"
				echo '<div class="copyRight-inner"  aria-labelledby="copyRight">';

					if ($copyright_text ) {
						echo '<div class="leftSide" '.($w_c_hide_show != true ? 'style="width: 100%;"' : '').'>';
							echo '<p class="'.($copyright_alignment ? esc_attr($copyright_alignment) : '').'">' . do_shortcode($copyright_text) . '</p>';
						echo '</div>';
					}

					if ($website_credit && $w_c_hide_show == true) {
						echo '<div class="rightSide">';
							echo '<p>' . wp_kses_post($website_credit) . '</p>';
						echo '</div>';
					}

				echo '</div>'; // .copyRight-inner
			echo '</div>'; // .container
		echo '</div>'; // .copyRight
	}

require_once THEME_PATH . '/mobfiles/mobFooter.php';
echo '</footer>'; // .mainFooter

require_once THEME_PATH . '/hotelPopup.php';

echo '</div>'; // .main-wrapper
wp_footer();
echo '</body>';
echo '</html>';
?>
