<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$sub_heading = $section['sub_heading'] ?: '';
$description = $section['description'] ?: '';
$select_dynamic_post = $section['select_dynamic_post'] ?: '';

echo '<section class="experience-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Experience Module - ' . esc_attr($uniqueSectionClass) . '"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($sub_heading){
            echo '<h3 class="subHeading" aria-label="subHeading">'.$sub_heading.'</h3>';
        }
        if($description){
            echo WysiwygReadMoreLess($description ,  'description');
        }
        if($select_dynamic_post){
            echo '<div class="experienceWrapper" >';
                $count = 0;
                foreach($select_dynamic_post as $post){
                    setup_postdata($post);
                    $count++;

                    $imgID = get_post_thumbnail_id($post) ?: defaultImageID();
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

                    if ($count % 2 == 1) {
                        echo '<div class="experienceItemRow">';
                    }

                        echo '<div class="experienceItem" aria-labelledby="Item-' . $count . '">';
                            echo '<a href="'.esc_url($itemUrl).'" class="coverAnchor" aria-label="'.$itemTitle.'"></a>';
                            echo '<div class="experienceImg" style="background: url('.$imgUrl.')no-repeat center/cover;">';
                                echo wp_get_attachment_image($imgID, 'full', false, ['loading' => 'eager']);
                            echo '</div>'; #experienceImg
                            echo '<div class="experienceData">';
                                if($itemTitle){
                                    echo '<h4>'.$itemTitle.'</h4>';
                                }
                                echo '<div class="hoverData" aria-hidden="false">';
                                    if($itemTitle){
                                        echo '<span class="hoverTitle">'.$itemTitle.'</span>';
                                    }
                                    if($duration){
                                        echo regularSalePrice(  regularPrice : $regular_price, salePrice : $sale_price);
                                    }
                                    if($short_description){
                                        echo '<div class="content proDescription">';
                                            echo $short_description;
                                        echo '</div>'; #content
                                    }else{
                                        echo '<div class="content">';
                                            $content_trimmed = wp_trim_words(wp_strip_all_tags($itemCont), 20, '...');
                                            echo $content_trimmed;
                                        echo '</div>'; #content
                                    }
                                echo '</div>';
                            echo '</div>'; #experienceData
                        echo '</div>'; #experienceItem

                    if ($count % 2 == 0) {
                        echo '</div>'; #experienceItemRow
                    }
                }
            echo '</div>'; #experienceWrapper
        }
    echo '</div>'; #container

echo '</section>'; #experience-module

?>