<?php

	// Unique Section Class
	$uniqueSectionClass = 'section-'.$currenSectionIndex;

	$bg = backgroundFromSection($section); //Fixed For All Section
	$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

	$heading_text = $section['heading_text'] ?: '';
	$content = $section['content'] ?: '';
	$select_post = $section['select_post'] ?: '';

	echo '<section class="box-with-title full-section dark '.esc_attr($uniqueSectionClass).'" 
	    role="Inspiration-'.esc_attr($uniqueSectionClass).'" aria-label="Box With Title - '.esc_attr($uniqueSectionClass).'"
	    '.($bg ? $bg : '').'
	    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';
	    echo '<div class="container">';
	    	echo '<div class="borderWrap">';
	    		echo '<div class="headingWrapper">';
	    			echo '<h2 class="heading">'.esc_html($heading_text).'</h2>';
	    		echo '</div>';
	    		if($select_post){
	    			echo '<div class="postWrapper">';
	    				echo '<a href="'.get_permalink($select_post->ID).'">'.esc_html($select_post->post_title).'</a>';
	    			echo '</div>'; #postWrapper
	    		}
	    		echo WysiwygReadMoreLess($content, 'contentWrap');
	    	echo '</div>'; #borderWrap
	    echo '</div>'; #container
	echo '</section>'; #box-with-title

?>