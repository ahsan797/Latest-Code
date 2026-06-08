<?php
// Unique Section Class (for styling only)
$uniqueSectionClass = 'section-' . $currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section);
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';
$heading = HeadingFromSection($section);

// ACF Repeater
$direction = $section['direction'] ?? '';
$content = $section['content'] ?? '';
$images = $section['images'] ?? [];

$logo_image = $section['logo_image'] ?? '';
$logo_image_direction = $section['logo_image_direction'] ?? '';

echo '<section class="ship-fifty-block full-section ' . esc_attr($section_text_color) . ' ' . esc_attr($uniqueSectionClass) . '" 
    role="50/50 Block"
    aria-label="50/50 Block"
    ' . ($bg ?: '') . '
    ' . ($section_id ? 'id="' . esc_attr($section_id) . '"' : '') . ' >';

    if($logo_image){
        echo '<div class="posImgWrapper '.esc_attr($logo_image_direction).'">';
            echo wp_get_attachment_image($logo_image, 'full', false, ['loading' => 'eager']);
        echo '</div>'; #posImgWrapper
    }
    

    echo '<div class="container">';
        echo HeadingFromSection($section);

        echo '<div class="flexWrapper '.esc_attr($direction).'">';
            echo '<div class="leftSide">';
                echo WysiwygReadMoreLess($content);
            echo '</div>'; #leftSide
            echo '<div class="rightSide">';
                if($images){
                    $imagesCounts = count($images);
                    echo '<div class="fiftyImagesWrapper '.($imagesCounts > 1 ? 'activeSlider' : '').'">';
                        foreach($images as $key => $img){
                            $image = $img['image'];

                            echo '<div class="imgItem">';
                                echo wp_get_attachment_image($image, 'full', false, ['loading' => 'eager']);
                            echo '</div>'; #imgItem

                        }
                    echo '</div>'; #fiftyImagesWrapper
                    if($imagesCounts > 1){
                        echo slickSliderArrows($uniqueSectionClass);
                    }
                }
            echo '</div>'; #rightSide
        echo '</div>'; #flexWrapper
    echo '</div>'; // container
echo '</section>'; #ship-fifty-block

?>
