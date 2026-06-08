<?php

// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$pageIndex = $args['pageIndex'] ?: '';
$theme = $args['theme'] ?? '';

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = $section['section_id'] ?: '';

$logo_image = $section['logo_image'] ?: '';
$logo_image_direction = $section['logo_image_direction'] ?: '';

$ship_tabs = $section['ship_tabs'] ?: '';

echo '<section class="pro-itinerary-carousel full-section '.esc_attr($theme ? $theme : $section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    aria-label="Pro Itinerary Carousel - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    if($logo_image){
        echo '<div class="posImgWrapper '.esc_attr($logo_image_direction).'">';
            echo getFocalImage($logo_image, 'logo_image', $pageIndex);
        echo '</div>'; #posImgWrapper
    }

    echo '<div class="container">';
        echo HeadingFromSection($section);
    echo '</div>'; #container

    
    if($ship_tabs){
        echo '<div class="shipTabWrapper">';
            echo '<div class="container">';
                echo '<div class="shipTabs">';
                    foreach($ship_tabs as $key => $tabs){
                        $tab_name = $tabs['tab_name'];

                        echo '<div class="shipTabsItem">';
                            echo '<a href="#tab'.$key.'">'.esc_html($tab_name).'</a>';
                        echo '</div>'; #shipTabsItem

                    }
                echo '</div>'; #shipTabs
            echo '</div>'; #container

            echo '<div class="shipTabsContent">';
                foreach($ship_tabs as $key => $tabs){
                    $tab_name = $tabs['tab_name'];
                    $tab_button = $tabs['button'] ?? '';
                    $tab_slides = $tabs['tab_slides'];
                    $tab_content = $tabs['tab_content'];

                    echo '<div class="TabsContItem repeaterArrows" id="tab'.$key.'">';
                        if($tab_slides){
                            $slideCounts = count($tab_slides);
                            echo '<div class="tabContSlider '.($slideCounts > 1 ? 'activeSlider' : '').' ">';
                                foreach($tab_slides as $key => $slide){
                                    $image = $slide['image'];

                                    echo '<div class="SlideItem">';
                                        echo getFocalImage($image, 'image', $pageIndex, $key);
                                    echo '</div>'; #SlideItem
                                }
                            echo '</div>'; #tabContSlider
                            if($slideCounts > 1){
                                echo slickSliderArrows('repeaterArrows-'.$key);
                            }
                        }
                        echo '<div class="tabContWysiwyg">';
                            echo '<div class="container">';
                                echo '<h3 class="tabName">'.esc_html($tab_name).'</h3>';
                                echo WysiwygReadMoreLess($tab_content , 'tabEditor');
                                
                                if($tab_button){
                                    echo dynamicBtnFromSection($tabs);
                                }
                            echo '</div>'; #container
                        echo '</div>'; #tabContWysiwyg
                    echo '</div>'; #TabsContItem

                }
            echo '</div>'; #shipTabsContent
        echo '</div>'; #shipTabWrapper
    }

echo '</section>'; #pro-itinerary-carousel

?>