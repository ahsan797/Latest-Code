<?php
// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$pageIndex = $args['pageIndex'] ?: '';
$theme = $args['theme'] ?? '';

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id'] ?: '');

$sub_heading = $section['sub_heading'] ?? '';
$layout_type = $section['layout_type'] ?? '';
$card_background = $section['card_background'] ?? '';
$card_text_color = $section['card_text_color'] ?? '';
$publication_logos = $section['publication_logos'] ?? [];


echo '<section class="'.($layout_type == 'updated' ? 'media-coverage-updated' : 'media-coverage-module').' full-section '.esc_attr($theme ? $theme : $section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Media Coverage Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').'>';
    
    echo '<div class="container">';
        if($sub_heading){
            echo '<p class="subHeading">'.esc_html($sub_heading).'</p>';
        }
        echo HeadingFromSection($section);

        if($layout_type == 'updated'){
            if($publication_logos){
                $counts = count($publication_logos);
                echo '<div class="publicationRepeater '.($counts > 1 ? 'activeSlider' : '').'" sliderItem="1" aria-label="List of media publications">';
                    foreach($publication_logos as $key => $rep){
                        $publication_logo = $rep['publication_logo'] ?: '';
                        $publication_name = $rep['publication_name'] ?: '';
                        $publication_designation = $rep['publication_designation'] ?: '';
                        $supporting_text = $rep['supporting_text'] ?: '';

                        echo '<div class="publicationItems '.esc_attr($card_text_color).'" 
                            '.($card_background ? 'style="background: '.$card_background.';"' : '').'
                             >';                        
                            if($supporting_text){
                                echo WysiwygReadMoreLess($supporting_text, 'supportingTextWrap');                           
                            }
                            echo '<div class="seperatorWrap"></div>';
                            if($publication_name || $publication_designation){
                                echo '<div class="nameDesignWrap">';
                                    if($publication_name){
                                        echo '<p class="name">'.esc_html($publication_name).'</p>';                            
                                    }
                                    if($publication_designation){
                                        echo '<p class="designation">'.esc_html($publication_designation).'</p>';
                                    }
                                echo '</div>'; #nameDesignWrap
                            }
                            if($publication_logo){
                                echo '<div class="publicationLogoWrap">';
                                    echo getFocalImage($publication_logo, 'publication_logo', $pageIndex, $key);       
                                echo '</div>'; #publicationLogoWrap                     
                            }
                        echo '</div>'; #publicationItems
                        
                    }
                echo '</div>'; #publicationRepeater
                if ( is_array($publication_logos) && (count($publication_logos) > 1) ) {
                    echo '<div class="publicationArrows  '.(count($publication_logos) > 1 ? 'activeSlider' : '').' ">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>'; #publicationArrows
                }

            }
        }else{
            if($publication_logos){
                $counts = count($publication_logos);
                echo '<div class="publicationRepeater '.($counts > 4 ? 'activeSlider' : '').'" sliderItem="4"  aria-label="List of media publications">';
                    foreach($publication_logos as $key => $rep){
                        $publication_logo = $rep['publication_logo'] ?: '';
                        $publication_name = $rep['publication_name'] ?: '';
                        $publication_designation = $rep['publication_designation'] ?: '';
                        $supporting_text = $rep['supporting_text'] ?: '';

                        echo '<div class="publicationItems"  >';
                            if($publication_logo){
                                echo getFocalImage($publication_logo, 'publication_logo', $pageIndex, $key);                            
                            }
                            if($publication_name){
                                echo '<p class="name">'.esc_html($publication_name).'</p>';                            
                            }
                            if($publication_designation){
                                echo '<p class="designation">'.esc_html($publication_designation).'</p>';
                            }
                            if($supporting_text){
                                echo $supporting_text;                           
                            }
                        echo '</div>'; #publicationItems
                        
                    }
                echo '</div>'; #publicationRepeater
                if ( is_array($publication_logos) && (count($publication_logos) > 1) ) {
                    echo '<div class="publicationArrows  '.(count($publication_logos) > 4 ? 'activeSlider' : '').' ">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>'; #publicationArrows
                }
            }
        }
    echo '</div>'; #container

echo '</section>'; #media-coverage-module

?>