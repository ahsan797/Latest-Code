<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$layout_type = $section['layout_type'] ?: '';
$static_data = $section['static_data'] ?: '';
$button_text = $section['button_text'] ?: '';
$select_dynamic_blogs = $section['select_dynamic_blogs'] ?: '';


echo '<section class="blogs full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Blogs - '.esc_attr($layout_type).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container">';
        echo HeadingFromSection($section);

        if($layout_type == 'dynamic'){
            $dynamicPosts = count($select_dynamic_blogs);
            echo '<div class="blogWrapper '.esc_attr($layout_type).'">';
                foreach($select_dynamic_blogs as $post){
                    setup_postdata($post);
                    // echo blogStructureFromRepeator($post, $button_text);
                    echo blogStructureFromRepeator( rep: $post, button_text: $button_text );
                }
                wp_reset_postdata();
            echo '</div>';
            if($dynamicPosts > 1){
                echo slickSliderArrows($uniqueSectionClass);
            }
        }else{
            $staticPosts = count($static_data);
            echo '<div class="blogWrapper '.esc_attr($layout_type).'">';
                foreach($static_data as $key => $sd){
                    // echo  blogStructureFromRepeator($sd);
                    echo blogStructureFromRepeator( rep: $sd);
                }
            echo '</div>';
            if($staticPosts > 1){
                echo slickSliderArrows($uniqueSectionClass);
            }
        }

    echo '</div>'; #container
echo '</section>'; #blogs

?>