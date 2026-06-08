<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?? '';

$gallery_images = $section['gallery_images'] ?? '';

echo '<section class="gallery full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Gallery"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container">';
        echo HeadingFromSection($section);

        if(is_array($gallery_images)){
            $counts = count($gallery_images);
            echo '<div class="galleryWrap '.( $counts > 3 ? 'activeSlider' : '' ).'">';
                foreach ($gallery_images as $key => $images) {
                         $image = $images['image'];
                         echo '<div class="galleryItem">';
                            echo '<a data-fancybox="pageGallery" href="'.wp_get_attachment_image_url($image, 'full').'">';
                                echo wp_get_attachment_image($image, 'full', false, ['loading' => 'eager']);
                            echo '</a>';
                         echo '</div>'; #galleryItem
                     }     
            echo '</div>'; #galleryWrap
            if($counts > 1){
                echo '<div class="galleryWrapArrows '.( $counts > 3 ? 'activeSlider' : '' ).'">';
                    echo slickSliderArrows($uniqueSectionClass);
                echo '</div>'; #galleryWrapArrows
            }
        }

    echo '</div>'; #container
echo '</section>'; #gallery

?>