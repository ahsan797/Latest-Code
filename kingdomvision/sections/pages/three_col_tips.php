<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$select_columns = $section['select_columns'] ?? '';
$columns = $section['columns'] ?? '';

echo '<section class="three-col-tips full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" aria-label="3 col Tips"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($columns){
            echo '<div class="columnsWrapper '.esc_attr($select_columns).'" role="list">';
                $number = 1;
                foreach ($columns as $key => $col) {
                    $content = $col['content'];
                    echo '<div class="columnsItem" role="listitem" >';
                        echo '<div class="stepNumber" aria-label="Step ' . esc_attr($number) . '">';
                            echo '<p>'.$number.'</p>';
                        echo '</div>';
                        if($content){
                            echo WysiwygReadMoreLess($content, 'columnContent');
                        }
                    echo '</div>';
                    $number++;
                }
            echo '</div>'; #columnsWrapper
        }
    echo '</div>'; #container
echo '</section>'; #three-col-tips

?>
