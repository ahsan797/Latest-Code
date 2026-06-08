<?php

// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$sub_heading = $section['sub_heading'] ?? '';
$content = $section['content'] ?? '';
$arrow_direction = $section['arrow_direction'] ?? '';
$pages = $section['pages'] ?? [];


echo '<section class="trending-blogs full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    role="region" aria-label="Trending Pages - '.esc_attr($uniqueSectionClass).'"
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

    // if( count($pages) > 1 && $arrow_direction == 'up' ){
    //     echo '<div class="container '.( count($pages) > 3 ? 'activeSlider' : '' ).'">';
    //         echo '<div class="aboveTheSlider">';
    //             echo slickSliderArrows($uniqueSectionClass);
    //         echo '</div>';
    //     echo '</div>'; #container
    // }
    
    if($pages){

        $countStaticBlog = count($pages);

        echo '<div class="container ofc '.( $countStaticBlog <= 3 ? 'centerData' : '' ).' ">';
            echo '<div class="blogWrapper '.( $countStaticBlog > 3 ? 'activeSlider' : '' ).' " carouselitem="3" >';
                foreach($pages as $key => $page){

                    $image = $page['image'];
                    $title = $page['title'];
                    $link = $page['link'];
					
                    echo '<div class="blogItem">';
                        if($link){ 
                            echo '<a href="'.esc_url($link).'" class="fullAnchor"></a>';
                        }
                        if($image){
                            echo '<div class="blogImg">';
                                echo getFocalImage($image, 'image', $pageIndex, $key);
                            echo '</div>'; #blogImg
                        }
                        if($title){
                            echo '<div class="blogCont ">';
                                    echo '<h3 class="title">'.$title.'</h3>';
                            echo '</div>'; #blogCont
                        }
                    echo '</div>'; #blogItem
                }
            echo '</div>'; #blogWrapper
        echo '</div>'; #container
    }


    if( count($pages) > 1){
        echo '<div class="container '.( count($pages) > 3 ? 'activeSlider' : '' ).'">';
            echo slickSliderArrows($uniqueSectionClass);
        echo '</div>'; #container
    }

echo '</section>'; #trending-blogs

?>