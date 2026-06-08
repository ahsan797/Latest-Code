<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$main_child = $section['main_child_heading'] ?? '';
$introductory_text = $section['introductory_text'] ?? '';
$award_sub_heading = $section['award_sub_heading'] ?? '';
$awards_repeater = $section['awards_repeater'] ?? [];
$sub_heading = $section['sub_heading'] ?? '';
$feature_repeater = $section['feature_repeater'] ?? [];
$cta_button = $section['cta_button'] ?? '';


echo '<section class="welcome-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" aria-label="Welcome Section"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        if($main_child){
            echo '<div class="subHeading ' . ($main_child ? 'main_child' : '') . '">';
                echo '<span>'.esc_html($main_child).'</span>';
            echo '</div>'; #subHeading
        }
        echo HeadingFromSection($section);
        if($introductory_text){
            echo WysiwygReadMoreLess($introductory_text ,  'introductoryText');
        }
        if($award_sub_heading){
            echo '<div class="subHeading">';
                echo '<span>'.esc_html($award_sub_heading).'</span>';
            echo '</div>'; #subHeading
        }
        if($awards_repeater){
            $countsAward = count($awards_repeater);
            echo '<div class="awardsRepeater '.( $countsAward > 1 ? 'activeSlider' : '').'" role="list" aria-label="Awards and Recognitions">';
                foreach($awards_repeater as $key => $rep){
                    $award_logo = $rep['award_logo'];
                    $award_url = $rep['award_url'];

                    echo '<div class="awardItems" role="listitem">';
                        if($award_url){
                            echo '<a href="'.esc_url($award_url).'" target="_blank" class="coverAnchor" aria-label="View award ' . ($key + 1) . ' details"></a>';                            
                        }
                        echo getFocalImage($award_logo, 'award_logo', $pageIndex, $key);
                    echo '</div>'; #awardItems
                }
            echo '</div>'; #awardsRepeater
            if($countsAward > 1){
                echo slickSliderArrows($uniqueSectionClass);
            }
        }
        if($sub_heading){
            echo '<div class="subHeading">';
                echo '<span>'.esc_html($sub_heading).'</span>';
            echo '</div>'; #subHeading
        }
        if($feature_repeater){
            echo '<div class="featureRepeater" role="list" aria-label="Key Features">';
                foreach($feature_repeater as $key => $fea){
                    $feature_color = $fea['feature_color'];
                    $thumb_icon = $fea['thumb_icon'];
                    $thumb_position = $fea['thumb_position'];
                    $thumb_transparency = $fea['thumb_transparency'];
                    $feature_title = $fea['feature_title'];

                    if ($thumb_transparency == 10) {
                        $thumb_transparency = 1;
                    } else {
                        $thumb_transparency = '0.' . $thumb_transparency;
                    }

                    echo '<div class="featureItem" role="listitem" '.($feature_color ? 'style="background: '.$feature_color.';"' : '').'>';
                        if($thumb_icon){
                            echo '<div class="thumbIcon '.esc_attr($thumb_position).'" '.($thumb_transparency ? 'style="opacity: '.$thumb_transparency.';"' : '').'>';
                                echo wp_get_attachment_image($thumb_icon, 'full', false, ['loading' => 'eager']);
                            echo '</div>';
                        }
                        if($feature_title){
                            echo '<p class="title">'.esc_html($feature_title).'</p>';
                        }
                    echo '</div>'; #featureItem

                }
            echo '</div>'; #featureRepeater
        }
        if($cta_button){

            $link_url = $cta_button['url'];
            $link_title = $cta_button['title'];
            $link_target = $cta_button['target'] ? $cta_button['target'] : '_self';

            echo '<div class="ctaButton">';
                echo '<a href="'.esc_url($link_url).'" target="'.esc_attr( $link_target ).'" class="btn" aria-label="' . esc_attr($link_title) . '">'.esc_html($link_title).'</a>';
            echo '</div>'; #ctaButton
        }

    echo '</div>'; #container

echo '</section>';

?>
