<?php
// Unique Section Class (for styling only)
$uniqueSectionClass = 'section-' . $currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section);
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';
$heading = HeadingFromSection($section);

// ACF Repeater
$galleries = $section['galleries'] ?? [];

echo '<section class="ship-gallery full-section ' . esc_attr($section_text_color) . ' ' . esc_attr($uniqueSectionClass) . '" 
    role="Ship Gallery"
    aria-label="Ship Gallery"
    ' . ($bg ?: '') . '
    ' . ($section_id ? 'id="' . esc_attr($section_id) . '"' : '') . ' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
 	echo '</div>'; // container
	if($galleries){
		$galleriesCounts = count($galleries);
		echo '<div class="galleriesWrapper '.($galleriesCounts > 1 ? 'activeSlider' : '').'">';
		foreach($galleries as $key => $gal){
			$image = $gal['image'];

			echo '<div class="galleryItem">';
				echo '<a data-fancybox="shipGallery" href="'. getFocalImageUrl($image, 'image', $pageIndex, $key) .'">';
					echo getFocalImage($image, 'image', $pageIndex, $key);
				echo '</a>';
			echo '</div>'; #galleryItem

		}
		echo '</div>'; #galleriesWrapper
		if($galleriesCounts > 1){
			echo slickSliderArrows($uniqueSectionClass);
		}
	}
   
echo '</section>';

?>
