<?php
// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$theme = $args['theme'] ?? '';

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id'] ?: '');

$sub_heading = $section['sub_heading'] ?: '';
$description = $section['description'] ?: '';
$show_recommend_box = $section['show_recommend_box'] ?: '';
$show_trip_box = $section['show_trip_box'] ?: '';
$active_carousel = $section['active_carousel'] ?: '';
$select_dynamic_post = $section['select_dynamic_post'] ?: [];
$dynamic_post_button_label = $section['dynamic_post_button_label'] ?: '';

$post_title_color = $section['post_title_color'] ?: '';
$box_text_color = $section['box_text_color'] ?: '';
$box_color = $section['box_color'] ?: '';

echo '<section class="itineraries-module full-section '.esc_attr($theme ? $theme : $section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Itineraries Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($sub_heading){
            echo '<h3 class="subHeading">'.$sub_heading.'</h3>';
        }
        if($description){
            echo WysiwygReadMoreLess($description ,  'description');
        }
        if($select_dynamic_post){
            echo '<div class="itinerariesWrapper" aria-label="Recommend Itineraries" >';
                // $count = 0;
                if($show_recommend_box == true){
                    foreach($select_dynamic_post as $index => $post){
                        setup_postdata($post);
                        

                        $imgID = get_post_thumbnail_id($post);
                        $imgUrl = wp_get_attachment_image_url( $imgID, 'full' );
                        $itemUrl   = get_the_permalink($post);
                        $itemTitle = get_the_title($post);
                        $itemCont  = get_post_field('post_content', $post);
                        
                        // Prduct Data
                        $product = get_field('product');
                        $duration = !empty($product['duration']) ? $product['duration'] : '';
                        $regular_price = !empty($product['regular_price']) ? $product['regular_price'] : '';
                        $sale_price = !empty($product['sale_price']) ? $product['sale_price'] : '';
                        $short_description = !empty($product['short_description']) ? $product['short_description'] : '';
                        $trip_locations = !empty($product['trip_locations']) ? $product['trip_locations'] : '';

                        if($index == 0){
                            echo '<div class="recommendWrapper">';
                                echo '<div class="recommendImg">';
                                    echo wp_get_attachment_image($imgID, 'full', false, ['loading' => 'eager']);
                                echo '</div>'; #recommendImg
                                echo '<div class="recommendData">';
                                    echo '<span class="staticData" aria-hidden="true">OUR EXPERTS RECOMMEND</span>';
                                    if($itemTitle){
                                        echo '<h3>'.$itemTitle.'</h3>';
                                    }
                                    if($trip_locations){
                                        echo '<p class="tripLocation">'.esc_html($trip_locations).'</p>';
                                    }
                                    if($duration){
                                        echo regularSalePrice(  regularPrice : $regular_price, salePrice : $sale_price);
                                    }
                                    if($short_description){
                                        echo '<div class="content proDescription">';
                                            echo $short_description;
                                        echo '</div>'; #content
                                    }
                                    if($itemUrl){
                                        echo '<a href="'.esc_url($itemUrl).'" class="btn">'.($dynamic_post_button_label ? $dynamic_post_button_label : 'View Trip').'</a>';
                                    }
                                echo '</div>'; #recommendData
                            echo '</div>'; #recommendWrapper
                            // Remove first item from array for second loop
                            unset($select_dynamic_post[$index]);
                        }
                        // $count++;
                    }
                    wp_reset_postdata();
                }

                if($show_trip_box == true){
                    echo '<div class="itinerariesItem mobileTripBox '.esc_attr($box_text_color).'" '.($box_color ? 'style="background: '.$box_color.';"' : '').' >';
                        echo customizedTripFromOption();
                    echo '</div>'; #itinerariesItem\
                }
                

                echo '<div class="itinerariesListing 
                '.($active_carousel == true && $show_trip_box == false && $show_recommend_box == false && count($select_dynamic_post) > 3 ? 'activeSlider' : '').' 
                '.esc_attr($box_text_color).' " 
                 >';
                    if($show_trip_box == true){
                        echo '<div class="itinerariesItem tripBox" '.($box_color ? 'style="background: '.$box_color.';"' : '').' >';
                            echo customizedTripFromOption();
                        echo '</div>'; #itinerariesItem\
                    }
                    // echo '<div class="mobileSlider">';
                        foreach($select_dynamic_post as $post){
                            setup_postdata($post);

                            echo '<div class="itinerariesItem " '.($box_color ? 'style="background: '.$box_color.';"' : '').' >';
                                // echo blogStructureFromRepeator($post, $dynamic_post_button_label);
                                echo blogStructureFromRepeator( rep: $post, button_text: $dynamic_post_button_label, titleColor:$post_title_color );
                            echo '</div>'; #itinerariesItem
                        }
                    // echo '</div>';
                echo '</div>'; #itinerariesListing
                if(count($select_dynamic_post) > 1){
                    echo '<div class="arrows '.( $active_carousel == true  ? 'activeSlider' : '').'">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>';
                }
                wp_reset_postdata();
            echo '</div>'; #itinerariesWrapper
        }
    echo '</div>'; #container
echo '</section>'; #itineraries-module

?>