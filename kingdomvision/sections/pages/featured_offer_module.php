<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$background_image = $section['background_image'] ?: '';
$panel_color = $section['panel_color'] ?: '';
$panel_color_opacity = $section['panel_color_opacity'] ?: '';
$pannel_direction = $section['pannel_direction'] ?: '';
$promotions = $section['promotions'] ?: '';
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

// Usage:
if ($panel_color_opacity == 1) {
    $opacity = 1;
} else {
    $opacity = '0.' . $panel_color_opacity;
}
$panel_color = hex2rgba($panel_color, $opacity);

$image_url = wp_get_attachment_image_url( $background_image, 'full' );

// role="Featured Offer Module" 
echo '<section class="featured-offer-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" aria-label="Featured Offer Module"
    '.($image_url ? 'style="background: url('.$image_url.')no-repeat center/cover;"' : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    if($promotions){
        // Count $promotions
        $counts = count($promotions);
        echo '<div class="panelWrapper '.esc_attr($pannel_direction).' " '.($panel_color ? 'style="background: '.esc_attr($panel_color).';"' : '').'>';
            echo '<div class="promoSlider '.($counts > 1 ? 'activeSlider' : '' ).'" aria-label="Featured Offers Slider">';
                foreach($promotions as $key => $pro){
                    $promo_title_tags = $pro['promo_title_tags'];
                    $promo_title = $pro['promo_title'];
                    $promo_label = $pro['promo_label'];
                    $cta_button = $pro['cta_button'];
                    $promo_description = $pro['promo_description'];

                    echo '<div class="promoItems">';
                        if($promo_title){
                            echo '<div class="promoTitle" aria-roledescription="slide" aria-label="Offer ' . ($key + 1) . ' of ' . esc_attr($counts) . '">';
                                if($promo_title_tags == 'h1'){
                                    echo '<h1 class="title">'.esc_html($promo_title).'</h1>';
                                }elseif($promo_title_tags == 'h3'){
                                    echo '<h3 class="title">'.esc_html($promo_title).'</h3>';
                                }elseif($promo_title_tags == 'h4'){
                                    echo '<h4 class="title">'.esc_html($promo_title).'</h4>';
                                }elseif($promo_title_tags == 'h5'){
                                    echo '<h5 class="title">'.esc_html($promo_title).'</h5>';
                                }elseif($promo_title_tags == 'h6'){
                                    echo '<h6 class="title">'.esc_html($promo_title).'</h6>';
                                }else{
                                    echo '<h2 class="title">'.esc_html($promo_title).'</h2>';
                                }
                            echo '</div>'; #promoTitle
                        }
                        if($promo_label){
                            echo '<div class="promoLabel">';
                                echo '<p>'.esc_html($promo_label).'</p>';
                            echo '</div>'; #promoLabel
                        }
                        if($promo_description){
                            echo WysiwygReadMoreLess($promo_description ,  'promoDescription');
                        }
                        if($cta_button){

                            $link_url = $cta_button['url'];
                            $link_title = $cta_button['title'];
                            $link_target = $cta_button['target'] ? $cta_button['target'] : '_self';

                            echo '<div class="ctaButton">';
                                echo '<a href="'.esc_url($link_url).'" target="'.esc_attr( $link_target ).'" class="btn" role="button" aria-label="' . esc_attr($link_title) . '">'.esc_html($link_title).'</a>';
                            echo '</div>'; #ctaButton
                        }
                    echo '</div>'; #promoItems

                }
            echo '</div>'; #promoSlider
            if($counts > 1){
                echo slickSliderArrows($uniqueSectionClass);
            }
        echo '</div>'; #panelWrapper
    }
echo '</section>'; #featured-offer-module
?>