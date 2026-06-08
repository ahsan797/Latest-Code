<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$image_direction = $section['image_direction'] ?: '';
$image = $section['image'] ?: '';
$sub_title = $section['sub_title'] ?: '';
$wysiwyg_editor = $section['wysiwyg_editor'] ?: '';
$button = $section['button'] ?: '';

echo '<section class="wide-image full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label=" Wide Image - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container '.esc_attr($image_direction).'">';
        echo '<div class="imgWrapper">';
            if($image){
                echo '<div class="ovalImg">';
                    echo getFocalImage($image, 'image', $pageIndex);
                echo '</div>'; #ovalImg
            }
        echo '</div>'; #imgWrapper
        echo '<div class="contWrapper">';
			if($sub_title){
                echo '<div class="subHeading">';
					echo '<span>'.$sub_title.'</span>';
				echo '</div>'; #subHeading
            }
            echo HeadingFromSection($section);
            if($wysiwyg_editor){
                echo WysiwygReadMoreLess($wysiwyg_editor ,  'wysiwygEditor');
            }
            if($button){
                echo dynamicBtnFromSection($section);
            }
        echo '</div>'; #contWrapper
    echo '</div>'; #container
echo '</section>'; #wide-image

?>