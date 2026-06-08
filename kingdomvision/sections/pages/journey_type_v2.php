<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$layout_type = $section['layout_type'] ?: '';
$journey_listings = $section['journey_listings'] ?: '';
$select_post = $section['select_post'] ?: '';

echo '<section class="journeyV2-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="Journey V2" aria-label="Journey V2 Module - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo HeadingFromSection($section);
    if($layout_type == 'dynamic'){
        if($select_post){
            echo '<div class="journeyWrapper activeSlider '.esc_attr($layout_type).'">';
                foreach($select_post as $post){
                    setup_postdata($post);

                    $link_url = get_the_permalink($post);
                    $title = get_the_title($post);
                    $description = get_the_content($post);
                    $thumbnail_id = get_post_thumbnail_id($post);

                    echo '<div class="journeyItem">';
                        echo '<div class="journeyItemJump">';
                            if($link_url){
                                echo '<a href="'.esc_url($link_url).'" class="fullAnchor"></a>';
                            }
                            echo '<div class="imgWrap">';
                                echo wp_get_attachment_image($thumbnail_id, 'full', false, ['loading' => 'eager']);
                                echo '<div class="dataWrap">';
                                    echo '<h3>'.esc_html($title).'</h3>';
                                echo '</div>'; #dataWrap
                            echo '</div>'; #imgWrap
                        echo '</div>'; #journeyItemJump
                    echo '</div>'; #journeyItem

                }
                wp_reset_postdata();
            echo '</div>'; #journeyWrapper
        }
    }else{
        if($journey_listings){
            echo '<div class="journeyWrapper activeSlider '.esc_attr($layout_type).'">';
                foreach($journey_listings as $key => $lists){
                    $link_url = $lists['url'] ?: '';
                    $title = $lists['title'] ?: '';
                    $image = $lists['image'] ?: '';

                    echo '<div class="journeyItem">';
                        echo '<div class="journeyItemJump">';
                            if($link_url){
                                echo '<a href="'.esc_url($link_url).'" class="fullAnchor"></a>';
                            }
                            echo '<div class="imgWrap">';
                                // echo wp_get_attachment_image($image, 'full', false, ['loading' => 'eager']);
                                echo getFocalImage($image, 'image', $pageIndex, $key);
                            echo '</div>'; #imgWrap
                            echo '<div class="dataWrap">';
                                echo '<h3>'.esc_html($title).'</h3>';
                            echo '</div>'; #dataWrap
                        echo '</div>'; #journeyItemJump
                    echo '</div>'; #journeyItem

                }
            echo '</div>'; #journeyWrapper
        }
    }
    echo slickSliderArrows($uniqueSectionClass);
echo '</section>'; #cta-module

?>

<style>
    section.journeyV2-module { padding: 60px 0; }
    .journeyWrapper{display:flex;}
    .journeyWrapper .slick-list{padding-left: 200px !important; padding-right:  300px !important;}
    .journeyWrapper .slick-track{display:flex;align-items:flex-end; }

    .journeyWrapper .journeyItem{width:200px; height: 400px !important; position: relative; margin: 0 7px;}
    .journeyWrapper .journeyItem .journeyItemJump { width: 100%; height: 100%; transition: 0.5s all ease-in; position:relative; }
    .journeyWrapper .journeyItem .journeyItemJump a.fullAnchor { width: 100%; height: 100%; display: block; position: absolute; z-index: 1; }


/*    .journeyWrapper .journeyItem.prevSlide{width:200px;}
    .journeyWrapper .journeyItem.slick-current{width:370px;height:300px;}
    .journeyWrapper .journeyItem.nextSlide{width:445px; height: 320px;}

    .journeyWrapper .journeyItem.nextSlide .journeyItemJump { padding-top: 0 !important; padding-bottom: 100px !important; }
    .journeyWrapper .journeyItem.slick-current .journeyItemJump { padding: 50px 0 50px 0; }*/

    .journeyWrapper .journeyItem .imgWrap{width:100%;height:100%;position:relative;}
    .journeyWrapper .journeyItem .imgWrap img{width:100%;height:100%;object-fit:cover;object-position:center center;}
    .journeyWrapper .journeyItem .dataWrap {display:block;position:absolute;bottom:0;width:100%;left:0;padding:10px;background: rgb(0, 0, 0 , 0.5);}
    .journeyWrapper .journeyItem .dataWrap h3{margin:0;color:#fff;font-size:14px;font-weight:400;text-transform:uppercase; }

/*    .journeyWrapper .journeyItem:nth-child(even) .journeyItemJump { padding-top: 50px; padding-bottom: 0;}
    .journeyWrapper .journeyItem:nth-child(odd) .journeyItemJump {padding-top: 0; padding-bottom: 50px; }*/

    .journeyWrapper .journeyItem:nth-child(3n + 1) { width: 250px !important; margin-top: 0px !important; margin-bottom: 50px !important; }
    .journeyWrapper .journeyItem:nth-child(3n + 2) { width: 330px !important; margin-top: 0 !important; margin-bottom: 100px !important; }
    .journeyWrapper .journeyItem:nth-child(3n + 3) { width: 430px !important; margin-top: 50px !important; margin-bottom: 150px !important; }




</style>
