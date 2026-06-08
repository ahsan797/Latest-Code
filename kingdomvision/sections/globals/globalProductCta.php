<?php

// 
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$section_id = $args['section_id'] ?: '';

$product_cta = get_field('product_cta' , 'options') ?: [];

$section_text_color = $product_cta['section_text_color'] ?: '';
$bg = backgroundFromSection($product_cta); //Fixed For All Section

$layout_style = $product_cta['layout_style'] ?: '';
$content = $product_cta['content'] ?: '';
$button = $product_cta['button'] ?: '';

echo '<section class="product-cta full-section '.esc_attr($layout_style).' '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Global Post CTA - '.esc_attr($uniqueSectionClass).'"
    '.($layout_style == 'ofc' ? ($bg ? $bg : '') : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container " >';
        if($layout_style == 'ic'){
            echo '<div class="backgroundWrapper" '.($bg ? $bg : '').'>';
        }
            echo HeadingFromSection($product_cta);
            if($content){
                echo WysiwygReadMoreLess($content ,  'content');
            }
            if($button){
                echo dynamicBtnFromSection($product_cta);
            }
        if($layout_style == 'ic'){
            echo '</div>'; #backgroundWrapper
        }
    echo '</div>'; #container

echo '</section>'; #product-cta

?>