<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$global_comments = $section['global_comments'] ?: '';
$multiple_comments = $section['multiple_comments'] ?: '';
$theme_comments = get_field('multiple_comments' , 'option'); // Global Comments

echo '<section class="customer-comments full-section dark '.esc_attr($uniqueSectionClass).'" 
    aria-label="Customer Comments Module - '.esc_attr($uniqueSectionClass).'"
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($global_comments == true){
            echo '<div class="multipleComments '.($global_comments ? 'global' : '').' activeSlider">';
                foreach($theme_comments as $key => $comments){
                    echo customComments($comments);
                }
            echo '</div>'; #multipleComments
            echo slickSliderArrows($uniqueSectionClass);
        }else{
            if($multiple_comments){
                $count = count($multiple_comments);
                echo '<div class="multipleComments '.($count > 4 ? 'activeSlider' : '').'">';
                    foreach($multiple_comments as $key => $comments){
                        echo customComments($comments);
                    }
                echo '</div>'; #multipleComments
                if($count > 1){
                    echo '<div class="customerArrows '.($count > 4 ? 'activeSlider' : '').'">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>';
                }
            }
        }
    echo '</div>'; #container
echo '</section>'; #customer-comments

?>