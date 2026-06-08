<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$layout_type = $section['layout_type'] ?: '';
$listings = $section['listings'] ?: '';
$select_post = $section['select_post'] ?: '';

echo '<section class="you-may-also-like-module full-section '.esc_attr($uniqueSectionClass).'" 
    aria-label="You May Also Like Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($layout_type == 'dynamic'){
            echo '<div class="listings-wrapper">';
                foreach($select_post as $key => $post){
                    setup_postdata($post);

                    $link_url = get_the_permalink($post);
                    $title = get_the_title($post);
                    $description = get_the_content($post);
                    $thumbnail_id = get_post_thumbnail_id($post);

                    echo '<div class="item" >';
                        if($link_url){
                            echo '<a href="'.esc_url($link_url).'" class="fullAnchor" aria-label="'.$title.'"></a>';
                        }
                        echo '<div class="img-wrap">';
                            echo wp_get_attachment_image($thumbnail_id, 'full', false, ['loading' => 'eager']);
                        echo '</div>'; #img-wrap
                        echo '<div class="item-data">';
                            if($title){
                                echo '<h3>'.esc_html($title).'</h3>';
                            }
                            if($description){
                                echo '<div class="desc-wrap">'. substr(strip_tags($description), 0, 50). '</div>';
                            }
                        echo '</div>'; #item-data
                    echo '</div>'; #item

                }
                wp_reset_postdata();
            echo '</div>'; #listings-wrapper
        }else{
            if($listings){
                echo '<div class="listings-wrapper">';
                    foreach($listings as $key => $lists){
                        $image = $lists['image'] ?: '';
                        $title = $lists['title'] ?: '';
                        $description = $lists['description'] ?: '';
                        $url = $lists['url'] ?: '';

                        echo '<div class="item" >';
                            if($url){
                                echo '<a href="'.esc_url($url).'" class="fullAnchor" aria-label="'.$title.'"></a>';
                            }
                            echo '<div class="img-wrap">';
                                echo getFocalImage($image, 'image', $pageIndex, $key);
                            echo '</div>'; #img-wrap
                            echo '<div class="item-data">';
                                if($title){
                                    echo '<h3>'.esc_html($title).'</h3>';
                                }
                                if($description){
                                    echo '<div class="desc-wrap">'. substr(strip_tags($description), 0, 50). '</div>';
                                }
                            echo '</div>'; #item-data
                        echo '</div>'; #item

                    }
                echo '</div>'; #listings-wrapper
            }
        }
    echo '</div>'; #container
echo '</section>'; #you-may-also-like-module

?>