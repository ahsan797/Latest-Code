<?php

// 
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$section_id = $args['section_id'] ?: '';

$page_cta = get_field('page_cta' , 'options') ?: [];

$section_text_color = $page_cta['section_text_color'] ?: '';
$bg = backgroundFromSection($page_cta); //Fixed For All Section

$layout_style = $page_cta['layout_style'] ?: '';
$content = $page_cta['content'] ?: '';
$button = $page_cta['button'] ?: '';

echo '<section class="product-cta full-section  '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Global Page CTA - '.esc_attr($uniqueSectionClass).'"
    '.($layout_style == 'ofc' ? ($bg ? $bg : '') : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container" >';
        if($layout_style == 'ic'){
            echo '<div class="backgroundWrapper" '.($bg ? $bg : '').'>';
        }
            echo HeadingFromSection($page_cta);
            if($content){
                echo WysiwygReadMoreLess($content ,  'content');
            }
            if($button){
                echo dynamicBtnFromSection($page_cta);
            }
        if($layout_style == 'ic'){
            echo '</div>'; #backgroundWrapper
        }
    echo '</div>'; #container

echo '</section>'; #product-cta

?>