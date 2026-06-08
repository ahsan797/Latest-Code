<?php
// Unique Section Class (for styling only)
$uniqueSectionClass = 'section-' . $currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section);
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';
$heading = HeadingFromSection($section);

$accordions = $section['accordions'] ?? [];

echo '<section class="ships-accordions full-section ' . esc_attr($section_text_color) . ' ' . esc_attr($uniqueSectionClass) . '" 
    role="Ships Accordions"
    aria-label="Ships Accordions"
    ' . ($bg ?: '') . '
    ' . ($section_id ? 'id="' . esc_attr($section_id) . '"' : '') . ' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);

        if($accordions){
            echo '<div class="shipsAccWrapper">';
                foreach ($accordions as $key => $value) {
                    $acc_title = $value['accordion_title'] ?? '';
                    $acc_content = $value['accordion_content'] ?? '';
                    $acc_images = $value['accordion_images'] ?? '';

                    echo '<div class="shipsAccItem">';
                        echo '<div class="accTitle">';
                            echo '<h3>'.esc_html($acc_title).'</h3>';
                        echo '</div>'; #accTitle
                        echo '<div class="accContent">';
                            echo '<div class="flexWrapper">';
                                if($acc_content){
                                    echo '<div class="leftSide '.( $acc_images == '' ? 'fullWidth' : '' ).'">';
                                        echo WysiwygReadMoreLess($acc_content);
                                    echo '</div>'; #leftSide
                                }
                                if($acc_images){
                                    echo '<div class="rightSide '.( $acc_content ? '' : 'fullWidth' ).'">';
                                        $imagesCounts = count($acc_images);
                                        echo '<div class="accImagesWrapper '.($imagesCounts > 1 ? 'activeSlider' : '').'">';
                                            foreach($acc_images as $key => $img){
                                                $image = $img['image'];

                                                echo '<div class="imgItem">';
                                                    echo '<a data-fancybox="'.esc_attr($uniqueSectionClass).'" href="'.wp_get_attachment_image_url($image, 'full').'" data-caption="'.esc_html($acc_title).'">';
                                                        // echo wp_get_attachment_image($image, 'full', false, ['loading' => 'eager']);
                                                        echo getFocalImage($image, 'image', $pageIndex, $key);
                                                    echo '</a>';
                                                echo '</div>'; #imgItem

                                            }
                                        echo '</div>'; #accImagesWrapper
                                        if($imagesCounts > 1){
                                            echo slickSliderArrows($uniqueSectionClass);
                                        }
                                    echo '</div>'; #rightSide
                                }
                            echo '</div>'; #flexWrapper
                        echo '</div>'; #accContent
                    echo '</div>'; #shipsAccItem
                }
            echo '</div>'; // shipsAccWrapper
        }
    echo '</div>'; // container
echo '</section>'; #ships-accordions

?>
