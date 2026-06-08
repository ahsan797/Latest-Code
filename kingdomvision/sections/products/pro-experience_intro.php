<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$main_child = $section['main_child_heading'] ?: '';
$image = $section['image'] ?: '';
$content = $section['content'] ?: '';
$button = $section['button'] ?: '';


echo '<section class="experience-intro full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" role="region" aria-label="Experience Intro Section"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        if($main_child){
            echo '<div class="subHeading ' . ($main_child ? 'main_child' : '') . '">';
                echo '<span>'.esc_html($main_child).'</span>';
            echo '</div>'; #subHeading
        }
        echo HeadingFromSection($section);
        echo '<div class="flexWrapper">';
            if($image){
                echo '<div class="leftSide">';
                    echo '<div class="imgWrapper">';
                            echo getFocalImage($image, 'image', $pageIndex);
                    echo '</div>'; #imgWrapper
                echo '</div>'; #leftSide
            }
            if($content || $button){
                echo '<div class="rightSide">';
                    if($content){
                        echo WysiwygReadMoreLess($content ,  'contWrapper');
                    }
                    if($button){
                        echo dynamicBtnFromSection($section);
                    }
                echo '</div>'; #rightSide
            }
        echo '</div>'; #flexWrapper
    echo '</div>'; #container
echo '</section>'; #experience-intro

?>
