<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

// $enabledisable = $section['enabledisable'] ?: '';
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

// Theme Option fields
$feefo_logo = get_field('feefo_logo' , 'option');
$feefo_shortcode = get_field('feefo_shortcode' , 'option');

// if($enabledisable == true){
    echo '<section class="reviews-module full-section dark '.esc_attr($uniqueSectionClass).'" aria-label="Reviews Module"
        '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
            echo '<div class="container">';
                if($feefo_logo){
                    echo '<div class="feefoLogo">';
                        echo wp_get_attachment_image($feefo_logo, 'full', false, ['loading' => 'eager']);
                    echo '</div>'; #feefoLogo
                }
                if($feefo_shortcode){
                    echo '<div class="feefoShortcode" role="region" aria-label="Feefo Reviews">';
                    echo do_shortcode($feefo_shortcode);  
                    echo '</div>'; #feefoShortcode
                }
            echo '</div>'; #container
    echo '</section>'; #reviews-module
// }
?>