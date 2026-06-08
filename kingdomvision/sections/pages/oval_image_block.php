<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$slider_lisitings = $section['slider_lisitings'] ?: '';

echo '<section class="oval-image-block full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Oval Image Block - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container ">';

        echo HeadingFromSection($section);

        if($slider_lisitings){
            $sliderCount = count($slider_lisitings);
            echo '<div class="listingWrapper '.($sliderCount > 1 ? 'activeSlider' : '').'">';
                foreach($slider_lisitings as $key => $listing){
                    $image_direction  = $listing['image_direction'] ?: '';
                    $image_shape      = $listing['image_shape'] ?: '';
                    $image            = $listing['image'] ?: '';
                    $img_border_width = $listing['image_border_width'] ?: '';
                    $img_border_color = $listing['image_border_color'] ?: '';
                    $sub_title        = $listing['sub_title'] ?: '';
                    $wysiwyg_editor   = $listing['wysiwyg_editor'] ?: '';
                    $button           = $listing['button'] ?: '';

                    echo '<div class="'.esc_attr($image_shape).' listingItem '.esc_attr($image_direction).'">';
                        echo '<div class="imgWrapper">';
                            if($image){
                                echo '<div class="ovalImg">';
                                    $style = 'border: '.esc_attr($img_border_width).'px solid '.esc_attr($img_border_color).'';
                                    echo getFocalImage($image, 'image', $pageIndex, $key, $style);
                                echo '</div>'; #ovalImg
                            }
                        echo '</div>'; #imgWrapper
                        echo '<div class="contWrapper">';
                            if($sub_title){
                                echo '<div class="oibsubHeading">';
                                    echo '<span>'.$sub_title.'</span>';
                                echo '</div>'; #oibsubHeading
                            }
                            echo HeadingFromSection($listing);
                            if($wysiwyg_editor){
                                echo WysiwygReadMoreLess($wysiwyg_editor ,  'wysiwygEditor');
                            }
                            if($button){
                                echo dynamicBtnFromSection($listing);
                            }
                        echo '</div>'; #contWrapper
                    echo '</div>';

                }
            echo '</div>'; #listingWrapper
            if($sliderCount > 1){
                echo slickSliderArrows($uniqueSectionClass);
            }
        }

    echo '</div>'; #container
echo '</section>'; #oval-image-block

?>