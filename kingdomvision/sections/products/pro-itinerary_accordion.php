<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$layout_style = $section['layout_style'];
$day_by_day = $section['day_by_day'];


echo '<section class="itinerary-accordion full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Itinerary Accordion- '.esc_attr($uniqueSectionClass).'" aria-label="Itinerary Accordion- '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);

        if($day_by_day){
            echo '<div class="itineraryAccWrapper '.esc_attr($layout_style).'">';
                
                foreach ($day_by_day as $key => $value) {
                    $count = $value['days_count'] ?? '';
                    $title = $value['title'] ?? '';
                    $sub_title = $value['sub_title'] ?? '';
                    $image = $value['image'] ?? '';
                    $image2 = $value['image_two'] ?? '';
                    // $two_size = '500x300';
                    $content = $value['content'] ?? '';
                    $acc_title = $value['accommodation_title'] ?? '';
                    $hotel = $value['hotel'] ?? [];

                    echo '<div class="itineraryAccItem">';
                        echo '<div class="accTitle">';
                            echo '<h3>'.($count ? '<span>'.esc_html($count).'</span>' : '' ).' '.esc_html($title).'</h3>';
                            if($layout_style == 'layoutTwo'){
                                if($sub_title){
                                    echo '<span class="subTitle">'.$sub_title.'</span>';
                                }
                            }
                        echo '</div>'; #accTitle
                        echo '<div class="accContent">';
                            if($image || $image2){
                                echo '<div class="mobileSliderWrap accordionImagesArrows">';
                                    echo '<div class="imagesWrapper '.(!$image2 ? 'full' : 'half').'">';
                                        if($image){
                                            echo '<div class="col '.($image2 ? 'half' : 'full').'">';
                                                // echo wp_get_attachment_image($image, 'full', false, ['loading' => 'eager']);
                                                echo getFocalImage($image, 'image', $pageIndex, $key);
                                            echo '</div>';
                                        }
                                        if($image2){
                                            echo '<div class="col '.($image ? 'half' : 'full' ).'">';
                                                // echo wp_get_attachment_image($image2, 'full', false, ['loading' => 'eager']);
                                                echo getFocalImage($image2, 'image_two', $pageIndex, $key);
                                            echo '</div>';
                                        }
                                    echo '</div>'; #imagesWrapper
                                    echo slickSliderArrows('accordionImagesArrows-'.$key);
                                echo '</div>'; #mobileSliderWrap
                            }
                            if($content){
                                echo WysiwygReadMoreLess($content ,  'contWrapper');
                            }
                            if($acc_title){
                                echo '<div class="accTitleWrapper">';
                                    echo '<span>'.esc_html($acc_title).'</span>';
                                echo '</div>'; #accTitleWrapper
                            }
                            if($hotel){
                                echo '<div class="hotelSliderWrapper repeaterArrows">';
                                    $counts = count($hotel);
                                    if($counts > 1){
                                        echo '<div class="hotelSliderArrows '.($counts > 2 ? 'activeSlider' : '').'">';
                                            echo slickSliderArrows('repeaterArrows-'.$key);
                                        echo '</div>'; #hotelSliderArrows
                                    }
                                    echo '<div class="hotelWrapper '.($counts > 2 ? 'activeSlider' : '').' ">';
                                        foreach($hotel as $posts){
                                            setup_postdata($posts);

                                            $featureImg = get_post_thumbnail_id($posts);
                                            $hotel_details = get_field('hotel_details' , $posts);
                                            $rating = $hotel_details['rating'] ?? '';
                                            $hotel_one_liner = $hotel_details['hotel_one_liner'] ?? '';

                                            // Get Data For Popup
                                            $banner_slider = get_field('banner_slider' , $posts);
                                            
                                            $hotelSliderImgs = [];
                                            if($banner_slider){
                                                foreach ($banner_slider as $key => $value) {
                                                    $desktop_image = $value['desktop_image']; 
                                                    $hotelSliderImgs[] =  wp_get_attachment_image_url($desktop_image, 'full'); 
                                                }
                                            }

                                            $builder  = get_field('product_page_builder', $posts->ID);
                                            $hotelDesc = $hotel_details['hotel_description'] ?? '';
                                            
                                            $highlights = [];
                                            if (!empty($builder)) {
                                                foreach ($builder as $section) {

                                                    // Flexible layout name check
                                                    if ($section['acf_fc_layout'] == 'four_col_bullets') {
                                                        $highlights = $section;
                                                        break;
                                                    }
                                                }
                                            }

                                            if($highlights){

                                                $allHighlights = array_merge(
                                                    $highlights['one_column_bullets'] ?? [],
                                                    $highlights['two_column_bullets'] ?? [],
                                                    $highlights['three_column_bullets'] ?? [],
                                                    $highlights['four_column_bullets'] ?? []
                                                );

                                                $highlightTexts = [];        
                                                foreach ($allHighlights as $item) {
                                                    // Skip empty labels
                                                    if (!empty(trim($item['label'] ?? ''))) {
                                                        $highlightTexts[] = trim($item['label']);
                                                    }
                                                }
                                                
                                            }
                                            $printable = '';

                                            if($rating){
                                                 $printable .= '<div class="starWrapper">';
                                                    for ($i = 0; $i < $rating; $i++) {
                                                        $printable .= '<i class="fa-solid fa-star"></i>';
                                                    }
                                                $printable .= '</div>'; #starWrapper
                                            }

                                            echo '<div class="hotelBox">';
                                                echo '<a href="javascript:;" 
                                                        hotelPermalink = '.get_the_permalink($posts).'
                                                        hotelTitle="'.get_the_title($posts).'"
                                                        hotelHighlights="'.esc_attr(wp_json_encode($highlightTexts)).'"
                                                        hotelDescriptions="'.esc_attr(strip_tags($hotelDesc)).'" 
                                                        hotelImages="'.esc_attr(wp_json_encode($hotelSliderImgs)).'"
                                                        hotelRating="'.esc_attr(wp_json_encode($printable)).'"
                                                        class="absolute hotelPopup"></a>';
                                                echo '<div class="hotelImg">';
                                                    echo wp_get_attachment_image($featureImg, 'full', false, ['loading' => 'eager']);
                                                echo '</div>'; #hotelImg
                                                echo '<div class="hotelContent">';
                                                    echo '<h3>'.get_the_title($posts).'</h3>';

                                                    if ($hotel_one_liner) {
                                                        echo '<p class="tripLocation" style="text-transform: capitalize;">' . esc_html($hotel_one_liner) . '</p>';
                                                    }
                                                    
                                                    if($rating){
                                                        echo $printable;
                                                    }
                                                    echo '<a href="javascript:;" 
                                                        hotelPermalink = '.get_the_permalink($posts).'
                                                        hotelTitle="'.get_the_title($posts).'"
                                                        hotelHighlights="'.esc_attr(wp_json_encode($highlightTexts)).'"
                                                        hotelDescriptions="'.esc_attr(strip_tags($hotelDesc)).'" 
                                                        hotelImages="'.esc_attr(wp_json_encode($hotelSliderImgs)).'"
                                                        hotelRating="'.esc_attr(wp_json_encode($printable)).'"
                                                        class="btn hotelPopup">Read More</a>';
                                                echo '</div>'; #hotelContent
                                            echo '</div>'; #hotelBox

                                        }
                                        wp_reset_postdata();
                                    echo '</div>'; #hotelWrapper
                                echo '</div>'; #hotelSliderWrapper
                            }
                        echo '</div>'; #accContent
                    echo '</div>'; #itineraryAccItem


                }
            echo '</div>'; #itineraryAccWrapper
        }

    echo '</div>'; #container

echo '</section>'; #itinerary-accordion

?>