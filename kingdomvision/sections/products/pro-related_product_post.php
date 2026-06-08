<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$select_productpost = $section['select_productpost'];
$dynamic_button_text = $section['dynamic_button_text'];


echo '<section class="related-ProPost full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Related Product/Post- '.esc_attr($uniqueSectionClass).'" aria-label="Related Product/Post- '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($select_productpost){
            echo '<div class="relatedWrapper " role="list">';
                foreach($select_productpost as $post){
                    setup_postdata($post);
                    // echo blogStructureFromRepeator($post, $dynamic_button_text);
                    echo blogStructureFromRepeator( rep: $post, button_text: $dynamic_button_text );
                }
                wp_reset_postdata();
            echo '</div>'; #relatedWrapper
        }

    echo '</div>'; #container

echo '</section>'; #related-ProPost

?>