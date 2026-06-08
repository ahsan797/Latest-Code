<?php

$ut_banner_logo = get_field('ut_banner_logo');
$overlay_transparency = get_field('overlay_transparency');
$banner_slider = get_field('banner_slider');
$autoplay = get_field('autoplay');
$autoplay_speed = get_field('autoplay_speed');
$parallax_effect = get_field('parallax_effect');


echo '<section class="pageBanner full-section" aria-labelledby="Page Banner" >';
    if($banner_slider){
        $countSlide = count($banner_slider);

        echo '<div class="bannerSliderWrapper '.($countSlide > 1 ? 'activeSlider' : '').'" data-autoplay="'.($autoplay ? 'true' : 'false').'" data-speed="'.($autoplay_speed ? $autoplay_speed : 3000).'">';
            foreach($banner_slider as $key => $slides){
                $select_imagevideo = $slides['select_imagevideo'];
                $desktop_image = $slides['desktop_image'];
                $mobile_image = $slides['mobile_image'];
                $overall_content_alignment = $slides['overall_content_alignment'];
                $heading_text = $slides['heading_text'];
                $content = $slides['content'];
                $button = $slides['button'];

                echo '<div class="itemWrapper">';
                    echo '<div class="itemMediaWrapper '.($parallax_effect ? 'activeParallax' : '').'">';
                        if($select_imagevideo == 'video'){
                            echo ywVideoFromRepeator($slides, $key);
                        }
                        else{
                            echo desktopMobileImage(section: $slides, key: $key);
                        }
                    echo '</div>'; #itemMediaWrapper

                    if($ut_banner_logo || $heading_text || $content || $button){
                        echo '<div class="itemDataWrapper ">';
                            echo '<div class="container">';
                                echo '<div class="postionWrapper '.esc_attr($overall_content_alignment).'">';
                                    if($ut_banner_logo){
                                        echo '<div class="banneLogo">';
                                            // echo wp_get_attachment_image($ut_banner_logo, 'full', false, ['loading' => 'eager']);
                                            echo getFocalImage($ut_banner_logo, 'ut_banner_logo' , 'single_module' );
                                        echo '</div>'; #banneLogo
                                    }
                                    if($heading_text){
                                        echo HeadingFromSection($slides);
                                    }
                                    if($content){
                                        echo WysiwygReadMoreLess($content);
                                    }
                                    if($button){
                                        echo dynamicBtnFromSection($slides);
                                    }
                                echo '</div>';
                            echo '</div>'; #container
                    echo '</div>'; #itemDataWrapper
                    }

                echo '</div>'; #itemWrapper

            }
        echo '</div>'; #bannerSliderWrapper
    }
echo '</section>'; #pageBanner

?>


