<?php


$postID = get_the_ID();

$ut_banner_logo = get_field('ut_banner_logo');
$overlay_transparency = get_field('overlay_transparency');
$autoplay = get_field('autoplay');
$autoplay_speed = get_field('autoplay_speed');
$parallax_effect = get_field('parallax_effect');

$banner_option = get_field('banner_option');
$desktop_image = get_field('desktop_image');
$mobile_image = get_field('mobile_image');
$banner_slider = get_field('banner_slider');

$overall_content_alignment = get_field('overall_content_alignment');
$heading_text = get_field('heading_text');
$content = get_field('content');
$button = get_field('button');

echo '<section class="pageBanner full-section" role="Page Banner" aria-labelledby="Page Banner" >';
    if($banner_slider){
        $countSlide = count($banner_slider);

        echo '<div class="bannerSliderWrapper '.($countSlide > 1 ? 'activeSlider' : '').' hotelSlider  " data-autoplay="'.($autoplay ? 'true' : 'false').'" data-speed="'.($autoplay_speed ? $autoplay_speed : 3000).'">';

            foreach($banner_slider as $key => $slides){
                $select_imagevideo = $slides['select_imagevideo'];
                $desktop_image = $slides['desktop_image'];
                $mobile_image = $slides['mobile_image'];
                

                echo '<div class="itemWrapper">';
                    echo '<div class="itemMediaWrapper '.($parallax_effect ? 'activeParallax' : '').'">';
                        if($select_imagevideo == 'video'){
                            echo ywVideoFromRepeator($slides );
                        }
                        else{
                            echo desktopMobileImage($slides, $key, $placeholder = 'hotel' , $lightBox = true);
                        }
                    echo '</div>'; #itemMediaWrapper
                echo '</div>'; #itemWrapper

            }
        echo '</div>'; #bannerSliderWrapper

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

                            echo HeadingFromSection('' , $postID);
                        }
                        if($content){
                            echo WysiwygReadMoreLess($content);
                        }
                        if($button){
                            echo dynamicBtnFromSection('' , $postID);
                        }
                    echo '</div>';
                echo '</div>'; #container
            echo '</div>'; #itemDataWrapper
        }
    }
echo '</section>'; #pageBanner

?>


