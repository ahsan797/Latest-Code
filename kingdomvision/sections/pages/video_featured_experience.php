<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$sub_heading = $section['sub_heading'] ?: '';
$description = $section['description'] ?: '';
$layout_type = $section['layout_type'] ?: '';

echo '<section class="video-featured-experience full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Video Featured Experience - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container  ">';
        echo HeadingFromSection($section);
        if($sub_heading){
            echo '<div class="subHeading">';
                echo '<span>'.$sub_heading.'</span>';
            echo '</div>';
        }
        if($description){
            echo WysiwygReadMoreLess($description ,  'description');
        }
        if($layout_type == 'ic'){
            echo ywVideoFromRepeator($section);
        }
    echo '</div>'; #container
    if($layout_type == 'ofc'){
        echo '<div class="container '.esc_attr($layout_type).'">'; 
            echo ywVideoFromRepeator($section);
        echo '</div>';
    }
echo '</section>'; #video-featured-experience

?>