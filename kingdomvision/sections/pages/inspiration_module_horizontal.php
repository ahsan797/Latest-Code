<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$section_text_color = $section['section_text_color'] ?: '';
$sub_heading = $section['sub_heading'] ?: '';
$description = $section['description'] ?: '';
$layout_type = $section['layout_type'] ?: '';
$traveller  = $section['traveller'] ?: '';
$select_posts = $section['select_posts'] ?: '';
$cta_button = $section['cta_button'] ?: '';


echo '<section class="inspiration-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Inspiration-'.esc_attr($uniqueSectionClass).'" aria-label="Inspiration - '.esc_attr($uniqueSectionClass).'"
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

        if($layout_type == 'dynmicData'){
            if($select_posts){
                echo '<div class="travelWrapper dynmicData horizontal" role="list" aria-label="Inspiration Destinations">';
                    foreach( $select_posts as $post ){
                        setup_postdata($post);

                        $link_url = get_the_permalink($post);
                        $title = get_the_title($post);
                        $thumbnail_id = get_post_thumbnail_id($post) ?: defaultImageID();
                        $background_image = '';

                        if ($thumbnail_id) {
                            $background_image = wp_get_attachment_image($thumbnail_id, 'full', false, ['loading' => 'eager']);
                        }

                        echo '<div class="travelItem" role="listitem">';
                            if($link_url){
                                echo '<a href="'.esc_url($link_url).'" class="coverAnchor"></a>';
                            }
                            if($background_image || $title){
                                echo '<div class="travelImg" aria-hidden="true">';
                                    echo $background_image;
                                    echo '<h3>'.esc_html($title).'</h3>';
                                echo '</div>'; #travelImg
                            }
                        echo '</div>'; #travelItem
                        
                    }
                    wp_reset_postdata();
                echo '</div>'; #travelWrapper
                
            }
        }else{
            if($traveller){
                echo '<div class="travelWrapper horizontal" role="list" aria-label="Inspiration Destinations">';
                    foreach($traveller as $key => $trav){
                        $background_image = $trav['background_image'] ?? '';
                        $title = $trav['title'] ?? '';
                        $link_url = $trav['link_url'] ?? '';

                        echo '<div class="travelItem" role="listitem">';
                            if($link_url){
                                echo '<a href="'.esc_url($link_url).'" class="coverAnchor"></a>';
                            }
                            if($background_image || $title){
                                echo '<div class="travelImg" aria-hidden="true">';
                                    echo getFocalImage($background_image, 'background_image', $pageIndex, $key);
                                     echo '<h3>'.esc_html($title).'</h3>';
                                echo '</div>'; #travelImg
                            }
                        echo '</div>'; #travelItem
                    }
                echo '</div>'; #travelWrapper
            }
        }

        if($cta_button){
            echo dynamicBtnFromSection($section);
        }

    echo '</div>'; #headingData
echo '</section>'; #inspiration-module

?>