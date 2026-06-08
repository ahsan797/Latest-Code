<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$show_in_desktop = $section['show_in_desktop'] ?? true;
$show_in_mobile = $section['show_in_mobile'] ?? true;

$layout_style = $section['layout_style'] ?? '';
$content = $section['content'] ?? '';
$button = $section['button'] ?? '';

echo '<section class="product-cta full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Product CTA - '.esc_attr($uniqueSectionClass).'" aria-label="Product CTA - '.esc_attr($uniqueSectionClass).'"
    '.($layout_style == 'ofc' ? ($bg ? $bg : '') : ' showDesktop="' . ($show_in_desktop ? 'showOnDesktop' : 'hideOnDesktop') . '" showMobile="' . ($show_in_mobile ? 'showOnMobile' : 'hideOnMobile') . '"').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    // '.($bg ? $bg : '').'
    
    echo '<div class="container '.esc_attr($layout_style).'" >';
        if($layout_style == 'ic'){
            echo '<div class="backgroundWrapper" '.($bg ? $bg : '').'>';
        }
            echo HeadingFromSection($section);
            if($content){
                echo WysiwygReadMoreLess($content ,  'content');
            }
            if($button){
                echo dynamicBtnFromSection($section);
            }
        if($layout_style == 'ic'){
            echo '</div>'; #backgroundWrapper
        }
    echo '</div>'; #container

echo '</section>'; #product-cta

?>