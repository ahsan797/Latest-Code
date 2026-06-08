<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$description = $section['description'] ?? '';
$opening_times = $section['opening_times'] ?? [];
$socialIconsBtn = $section['social_icons'] ?? '';
$social_icons = get_field('social_icons', 'options');


echo '<section class="opening-hours full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'"  aria-label="Opening Hours"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        
        echo HeadingFromSection($section);
        if($description){
            echo WysiwygReadMoreLess($description);
        }
        if($opening_times){
            echo '<div class="openingWrapper">';
                echo '<ul>';
                    foreach ($opening_times as $key => $value) {
                        $label = $value['label'];
                        $time = $value['time'];

                        echo '<li><span class="label">'.esc_html($label).'</span> <span class="time">'.esc_html($time).'</span></li>';

                    }
                echo '</ul>';
            echo '</div>'; #openingWrapper
        }
        if($socialIconsBtn == true){
            if($social_icons){
                echo '<div class="socialWrapper">';
                    echo '<ul class="social_icons">';
                        foreach($social_icons as $key => $icons){
                            $icon = $icons['icons'] ?: '' ;
                            $url = $icons['url'] ?: '' ;
                            
                            echo '<li>';
                                if($url){
                                    echo '<a href="'.esc_url( $url ).'" target="_blank" aria-label="'.esc_url( $url ).'"><i class="'.$icon.'"></i></a>';
                                }else{
                                    echo '<i class="'.$icon.'"></i>';
                                }
                            echo '</li>';
                            
                        }
                    echo '</ul>'; #social_icons
                echo '</div>'; #socialWrapper
            }
        }

    echo '</div>'; #container
echo '</section>'; #opening-hours

?>
