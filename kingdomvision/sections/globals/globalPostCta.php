<?php

// 
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$section_id = $args['section_id'] ?: '';
$theme = $args['theme'] ?: '';

$post_cta = get_field('post_cta' , 'options') ?: [];

$section_text_color = $post_cta['section_text_color'] ?: '';
$bg = backgroundFromSection($post_cta); //Fixed For All Section

$layout_style = $post_cta['layout_style'] ?: '';
$content = $post_cta['content'] ?: '';
$button = $post_cta['button'] ?: '';

echo '<section class="product-cta full-section '.esc_attr($theme ? $theme : $section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Global Post CTA - '.esc_attr($uniqueSectionClass).'"
    '.($layout_style == 'ofc' ? ($bg ? $bg : '') : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container " >';
        if($layout_style == 'ic'){
            echo '<div class="backgroundWrapper" '.($bg ? $bg : '').'>';
        }
            echo HeadingFromSection($post_cta);
            if($content){
                echo WysiwygReadMoreLess($content ,  'content');
            }
            if($button){
                echo dynamicBtnFromSection($post_cta);
            }
        if($layout_style == 'ic'){
            echo '</div>'; #backgroundWrapper
        }
    echo '</div>'; #container

echo '</section>'; #product-cta

?>