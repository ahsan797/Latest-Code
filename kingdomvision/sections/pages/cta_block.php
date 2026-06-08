<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$description = $section['description'] ?: '';
$button = $section['button'] ?: '';

$finger_image    = $section['finger_image'] ?: '';
$finger_position = $section['finger_position'] ?: '';
$sub_heading = $section['sub_heading'] ?: '';

// role="CTA"
echo '<section class="cta-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="CTA Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    if($finger_image):
        echo '<div class="thumbIcon '.$finger_position.'">';
            echo getFocalImage($finger_image, 'finger_image', $pageIndex);
        echo '</div>';
    endif;

    echo '<div class="container">';
        if($sub_heading){
            echo '<p class="subHeading">'.esc_html($sub_heading).'</p>';
        }
        echo HeadingFromSection($section);
        if($description){
            echo WysiwygReadMoreLess($description ,  'description');
        }
        if($button){
            echo dynamicBtnFromSection($section);
        }
    echo '</div>'; #container
echo '</section>'; #cta-module

?>