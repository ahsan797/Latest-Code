<?php
// Unique Section Class (for styling only)
$uniqueSectionClass = 'section-' . $currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section);
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';


// ACF Repeater
$heading_option = $section['heading_option'] ?? '';
$ship_details = $section['ship_details'] ?? [];


echo '<section class="shipDetails full-section ' . esc_attr($section_text_color) . ' ' . esc_attr($uniqueSectionClass) . '"
    role="Ship Specifications"
    aria-label="Ship Specifications"
    ' . ($bg ?: '') . '
    ' . ($section_id ? 'id="' . esc_attr($section_id) . '"' : '') . ' ">';
    echo '<div class="container">';    
        echo HeadingFromSection(section:$section, class: 'mobileTitle '.($heading_option ? 'desktopShow' : 'mobileShow') );
        echo '<div class="shiplisiting">';
            foreach($ship_details as $key => $details){
                $label = $details['label'];
                $label_text = $details['label_text'];
                $icons = $details['icons'];
                
                echo '<div class="shipItem '.esc_attr($label).'">';
                    if($icons == 'built'){
                        echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/built-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
                    }elseif($icons == 'guest'){
                        echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/guest-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
                    }elseif($icons == 'crew'){
                        echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/crew-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
                    }elseif($icons == 'bedroom'){
                        echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/bedroom-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
                    }elseif($icons == 'restaurant'){
                        echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/restaurant-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
                    }
                    if($label){
                        echo '<p class="label">'.esc_attr($label).'</p>';
                    }
                    if($label_text){
                        echo '<p class="label_text">'.esc_attr($label_text).'</p>';
                    }
                echo '</div>'; #shipItem
                
            }
        echo '</div>'; #shiplisiting
    echo '</div>'; #container
echo '</section>'; #shipDetails

?>
