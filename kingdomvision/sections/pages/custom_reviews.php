<?php
// Unique Section Class
$uniqueSectionClass = 'section-'.$currenSectionIndex;

$section_text_color = $section['section_text_color'] ?: '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$select_reviews = $section['select_reviews'] ?: '';

// role="Custom Reviews"
echo '<section class="custom-reviews-module full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Custom Reviews - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

    echo '<div class="container">';
        echo HeadingFromSection($section);
        if($select_reviews){
            echo '<div class="custReviews" role="list">';
                foreach($select_reviews as $post){
                    setup_postdata($post);

                    $rating      = get_post_meta(get_the_ID(), 'stars', true);
                    $review_url  = esc_url(get_post_meta(get_the_ID(), 'review_url', true));
                    $author_name = esc_html(get_post_meta(get_the_ID(), 'author_name', true));
                    $title       = esc_html(get_the_title());
                    $excerpt     = wp_strip_all_tags(get_the_content());
                    $excerpt     = esc_html(mb_strimwidth($excerpt, 0, 100, '...'));

                    echo '<div class="revItem" role="listitem" aria-label="Review by ' . esc_attr($author_name) . '">';
                        echo '<div class="revStar">';
                            if($rating){
                                echo '<div class="starWrapper">';
                                    for ($i = 0; $i < $rating; $i++) {
                                        echo '<i class="fa-brand fa-star"></i>';
                                    }
                                echo '</div>'; #starWrapper
                            }
                        echo '</div>'; #revStar
                        echo '<div class="revCont">';
                            echo '<h3>'.$title.'</h3>';
                        echo '</div>'; #revTitle
                        echo '<div class="revCont">';
                            echo $excerpt;
                        echo '</div>'; #revCont
                    echo '</div>'; #revItem

                }
            echo '</div>'; #custReviews
            echo slickSliderArrows($uniqueSectionClass);
            echo '<div class="feefoLogoWrap">';
                echo '<img src="'.THEME_URL.'/images/feefLogoImg.png" alt="feefo Logo"/>';
            echo '</div>'; #feefoLogoWrap
        }
        wp_reset_postdata();
    echo '</div>'; #container
echo '</section>'; #custom-reviews-module

?>