<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$column_one = $section['column_one'] ?? '';
$contentOne = $column_one['content'] ?? '';
$column_two = $section['column_two'] ?? '';
$contentTwo = $column_two['content'] ?? '';

echo '<section class="fifty-fifty-content-block full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'"  aria-label="50 / 50 Content Block"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';

        echo '<div class="leftSide">';
            echo HeadingFromSection($column_one);
            if($contentOne){
                echo WysiwygReadMoreLess($contentOne, 'colContent');
            }
        echo '</div>'; #leftSide
        echo '<div class="rightSide">';
            echo HeadingFromSection($column_two);
            if($contentTwo){
                echo WysiwygReadMoreLess($contentTwo, 'colContent');
            }
        echo '</div>'; #rightSide

    echo '</div>'; #container
echo '</section>'; #fifty-fifty-content-block

?>
