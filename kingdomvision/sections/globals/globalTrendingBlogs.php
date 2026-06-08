<?php

// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$pageIndex = $args['pageIndex'] ?: '';
$theme = $args['theme'] ?? '';

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = $section['section_id'] ?: '';

$sub_heading = $section['sub_heading'] ?: '';
$content = $section['content'] ?: '';
$layout_type = $section['layout_type'] ?: '';
$carousel_type = $section['carousel_type'] ?: '';
$select_carousel_item = $section['select_carousel_item'] ?: '';
$arrow_direction = $section['arrow_direction'] ?: '';

$box_text_color = $section['box_text_color'] ?? '';
$box_color = $section['box_color'] ?? '';
$box_title_color = $section['box_title_color'] ?? '';

$static_blogs = $section['static_blogs'] ?: [];
$select_dynamic_blogs = $section['select_dynamic_blogs'] ?: [];
$dynamic_post_button_label = $section['dynamic_post_button_label'] ?: '';

$button = $section['button'] ?? '';

echo '<section class="trending-blogs full-section '.esc_attr($theme ? $theme : $section_text_color).' '.esc_attr($uniqueSectionClass).' global" 
    role="region" aria-label="Trending Blogs - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').'>';
    
    echo '<div class="container ">';
        if($sub_heading){
            echo '<p class="subHeading">'.esc_html($sub_heading).'</p>';
        }
        echo HeadingFromSection($section);
        if($content){
            echo WysiwygReadMoreLess($content ,  'contWrap');
        }
    echo '</div>'; #container

    // if (
    //     (
    //         (is_array($static_blogs) && count($static_blogs) > 1) ||
    //         (is_array($select_dynamic_blogs) && count($select_dynamic_blogs) > 1)
    //     )
    //     && $arrow_direction == 'up'
    // ) {
    //     echo '<div class="container '.( count($select_dynamic_blogs) > 3 || count($static_blogs) > 3 ? 'activeSlider' : '' ).'">';
    //         echo '<div class="aboveTheSlider">';
    //             echo slickSliderArrows($uniqueSectionClass);
    //         echo '</div>';
    //     echo '</div>'; #container
    // }
    
    if($static_blogs || $select_dynamic_blogs ){

            if($layout_type == 'dynamic'){
                if($select_dynamic_blogs){
                    $countDynamicBlog = count($select_dynamic_blogs);
                    echo '<div class="container '.esc_attr($carousel_type).' '.( $countDynamicBlog <= 3 ? 'centerData' : '' ).' ">';
                        echo '<div class="blogWrapper '.( $countDynamicBlog > 3 ? 'activeSlider' : '' ).'  '.esc_attr($layout_type).' '.esc_attr($carousel_type).' '.esc_attr($box_text_color).'" carouselitem="'.esc_attr($select_carousel_item).'">';
                            foreach($select_dynamic_blogs as $post){
                                setup_postdata($post);
                                // echo blogStructureFromRepeator($post , $dynamic_post_button_label);
                                echo blogStructureFromRepeator( rep: $post, button_text: $dynamic_post_button_label, titleColor: $box_title_color, boxColor: $box_color );
                            }
                            wp_reset_postdata();
                        echo '</div>'; #blogWrapper
                    echo '</div>'; #container
                }
            }else{
                if($static_blogs){

                    $countStaticBlog = count($static_blogs);

                    echo '<div class="container '.esc_attr($carousel_type).' '.( $countStaticBlog <= 3 ? 'centerData' : '' ).' ">';
                        echo '<div class="blogWrapper '.( $countStaticBlog > 3 ? 'activeSlider' : '' ).' '.esc_attr($layout_type).' '.esc_attr($carousel_type).' '.esc_attr($box_text_color).'" carouselitem="'.esc_attr($select_carousel_item).'" >';
                            foreach($static_blogs as $key => $sb){
                                // echo  blogStructureFromRepeator($sb, '', $pageIndex, $key);
                                echo blogStructureFromRepeator( rep: $sb, pageIndex: $pageIndex, repKey: $key, titleColor: $box_title_color, boxColor: $box_color);
                            }
                        echo '</div>'; #blogWrapper
                    echo '</div>'; #container
                }
            }
    }


    if (
        (is_array($static_blogs) && ($layout_type == 'dynamic' && count($select_dynamic_blogs) > 1) ) ||
        (is_array($select_dynamic_blogs) && ($layout_type == 'static' && count($static_blogs) > 1) )
    ) {
        echo '<div class="container '.esc_attr( $carousel_type == 'ofc' ? 'outofcontainer' : 'ic' ).' 
        '.( 
            ($layout_type == 'dynamic' && count($select_dynamic_blogs) > 3) || 
            ($layout_type == 'static' && count($static_blogs) > 3) ? 'activeSlider' : '' 
        ).' ">';
            echo slickSliderArrows($uniqueSectionClass);
        echo '</div>'; #container
    }

    if($button){
        echo '<div class="container">';
            echo dynamicBtnFromSection($section);
        echo '</div>'; #container
    }

echo '</section>'; #trending-blogs

?>