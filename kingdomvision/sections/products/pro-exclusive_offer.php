<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$sub_title = $section['sub_title'];
$content = $section['content'];
$multiple_buttons = $section['multiple_buttons'];


echo '<section class="exclusive-offer full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Exclusive Offer - '.esc_attr($uniqueSectionClass).'" aria-label="Exclusive Offer - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($sub_title){
            echo '<div class="subTitle">';
                echo '<span>'.esc_attr($sub_title).'</span>';
            echo '</div>'; #subTitle
        }
        if($content){
            echo WysiwygReadMoreLess($content ,  'contWrapper');
        }
        if($multiple_buttons){
            echo '<div class="multibtnWrapper">';
                foreach($multiple_buttons as $key => $buttons){
                    echo dynamicBtnFromSection($buttons);
                }
            echo '</div>'; #multibtnWrapper
        }

    echo '</div>'; #container

echo '</section>'; #exclusive-offer

?>