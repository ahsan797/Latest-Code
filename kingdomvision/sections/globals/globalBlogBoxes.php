<?php

// Global Section
$section = $args['section'] ?: [];
// Unique Section Class
$uniqueSectionClass = $args['uniqueSectionClass'] ?: '';
$theme = $args['theme'] ?? '';

$section_text_color = $section['section_text_color'] ?? '';
$bg = backgroundFromSection($section); //Fixed For All Section
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

$select_posts = $section['select_posts'] ?: '';

echo '<section class="blog-filter noAjaxFilter full-section global '.esc_attr($theme ? $theme : $section_text_color).' '.esc_attr($uniqueSectionClass).'" 
    aria-label="Blogs - '.esc_attr($uniqueSectionClass).'"
    '.($bg ? $bg : '').'
    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
    
    echo '<div class="container">';
        echo HeadingFromSection($section);

        echo '<div class="postFilterResult horizontal ">';
        	if($select_posts){
        		foreach ($select_posts as $post) {
        			setup_postdata($post);
		        	echo blogStructureFromRepeator( rep: $post, button_text: 'Read More' );
        		}
        		wp_reset_postdata();
        	}
        echo '</div>'; #postFilterResult

		// echo '<div class="postFilterPagination"></div>';
        if(is_array($select_posts) && count($select_posts) >= 5){ 
        	echo '<div class="loadWrapper">';
                    echo '<a href="javascript:;" id="loadMoreBtn" class="btn" style="display:none">View more</a>';
        	echo '</div>'; #loadWrapper
        }
    echo '</div>'; #container
echo '</section>'; #blog-filter

?>