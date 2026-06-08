<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$section_text_color = $section['section_text_color'] ?: '';
$sub_heading = $section['sub_heading'] ?: '';
$description = $section['description'] ?: '';
$layout_type = $section['layout_type'] ?: '';
$carousel_type = $section['carousel_type'] ?: '';
$traveller  = $section['traveller'] ?: '';
$select_posts = $section['select_posts'] ?: '';
$cta_button = $section['cta_button'] ?: '';

// role="Inspiration-'.esc_attr($uniqueSectionClass).'"
echo '<section class="inspiration-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Inspiration - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($sub_heading){
            echo '<div class="subHeading">';
                echo '<span >'.esc_html($sub_heading).'</span>';
            echo '</div>'; #subHeading
        }
        if($description){
            echo WysiwygReadMoreLess($description ,  'descriptionWrapper');
        } 
    echo '</div>'; #headingData

        if($layout_type == 'dynmicData'){
            if($select_posts){
                echo '<div class="container '.esc_attr($carousel_type).'">';
                    $countPost = count($select_posts);
                    echo '<div class="travelWrapper '.($countPost > 4 ? 'activeSlider' : '').'  dynmicData '.esc_attr($carousel_type).' " aria-label="Inspiration Destinations">';
                        foreach( $select_posts as $post ){
                            setup_postdata($post);

                            $link_url = get_the_permalink($post);
                            $title = get_the_title($post);
                            $thumbnail_id = get_post_thumbnail_id($post) ?: defaultImageID();
                            $background_image = '';

                            $product = get_field('product');
                            $duration = !empty($product['duration']) ? $product['duration'] : '';
                            $per_person = !empty($product['per_person']) ? $product['per_person'] : '' ; 
                            $regular_price = !empty($product['regular_price']) ? $product['regular_price'] : '' ; 
                            $sale_price = !empty($product['sale_price']) ? $product['sale_price'] : '' ;
                            $short_description = !empty($product['short_description']) ? $product['short_description'] : '' ;; 

                            if ($thumbnail_id) {
                                $background_image = wp_get_attachment_image($thumbnail_id, 'full', false, ['loading' => 'lazy']);
                            }

                            echo '<div class="travelItem">';
                                if($link_url){
                                    echo '<a href="'.esc_url($link_url).'" class="coverAnchor" aria-label="'.$title.'"></a>';
                                }
                                if($background_image){
                                    echo '<div class="travelImg" aria-hidden="true">';
                                        echo $background_image;
                                    echo '</div>'; #travelImg
                                }
                                if($title){
                                    echo '<div class="travelData">';
                                        echo '<h3>'.esc_html($title).'</h3>';
                                        if($duration || $per_person || $regular_price || $sale_price || $short_description){
                                            echo '<div class="hoverData">';
                                                echo '<span class="title">'.esc_html($title).'</span>';
                                                if($short_description){
                                                    echo '<p>'.wp_trim_words($short_description, 20, '...' ).'</p>';
                                                }
                                                if($duration){
                                                    echo regularSalePrice( perNight : true, duration : $duration, regularPrice : $regular_price, salePrice : $sale_price);
                                                }
                                            echo '</div>'; #hoverData
                                        }
                                        echo '<a href="'.esc_url($link_url).'" class="btn" aria-label="Explore ' . esc_attr($title) . '">Explore this trip</a>';
                                    echo '</div>'; #travelData
                                }
                            echo '</div>'; #travelItem
                            
                        }
                        wp_reset_postdata();
                    echo '</div>'; #travelWrapper
                echo '</div>'; #container
                if($countPost > 1){
                    echo '<div class="container '.($countPost > 4 ? 'activeSlider' : '').'">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>'; #container
                }
            }
        }else{
            if($traveller){
                echo '<div class="container '.esc_attr($carousel_type).'">';
                    $countStatic = count($traveller);
                    echo '<div class="travelWrapper '.($countStatic > 4 ? 'activeSlider' : '').' '.esc_attr($carousel_type).' "  aria-label="Inspiration Destinations">';
                        foreach($traveller as $key => $trav){
                            $background_image = $trav['background_image'] ?? '';
                            $title = $trav['title'] ?? '';
                            $link_url = $trav['link_url'] ?? '';

                            echo '<div class="travelItem" >';
                                if($link_url){
                                    echo '<a href="'.esc_url($link_url).'" class="coverAnchor" aria-label="'.$title.'"></a>';
                                }
                                if($background_image){
                                    echo '<div class="travelImg" aria-hidden="true">';
                                        echo getFocalImage($background_image, 'background_image', $pageIndex, $key);
                                    echo '</div>'; #travelImg
                                }
                                if($title){
                                    echo '<div class="travelData">';
                                        echo '<h3>'.esc_html($title).'</h3>';
                                    echo '</div>'; #travelData
                                }
                            echo '</div>'; #travelItem
                        }
                    echo '</div>'; #travelWrapper
                echo '</div>'; #container
                if($countStatic > 1){
                    echo '<div class="container '.($countStatic > 4 ? 'activeSlider' : '').'">';
                        echo slickSliderArrows($uniqueSectionClass);
                    echo '</div>'; #container
                }
            }
        }
        if($cta_button){
            echo '<div class="container">';
                    echo dynamicBtnFromSection($section);
            echo '</div>'; #container
        }
echo '</section>'; #inspiration-module

?>