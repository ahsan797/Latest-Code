<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$background_color = $section['background_color'] ?: '';
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

// Section Fields
$sub_heading = $section['sub_heading'] ?: '';

// Theme Option Fields
$background_color_opt       = get_field('background_color' , 'option') ?: '';
$sub_heading_opt        = get_field('sub_heading' , 'option') ?: '';

// if($enabledisable_feeds == true){
echo '<section class="instagram-feed-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" role="Instagram Feeds Module" aria-label="Instagram Feeds Module"
    style="background: '.($background_color ? $background_color : $background_color_opt).';"
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);

        if($sub_heading || $sub_heading_opt ){
            echo '<div class="subHeading">';
                echo '<span>'.($sub_heading ? esc_html($sub_heading) : esc_html($sub_heading_opt)).'</span>';
            echo '</div>'; #subHeading
        }
		echo do_shortcode('[instagram-feed feed=1]');        
    echo '</div>'; #container
echo '</section>'; #instagram-feed-module
// }
?>