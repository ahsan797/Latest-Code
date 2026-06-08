<?php

	// Unique Section Class
	$uniqueSectionClass = 'section-'.$currenSectionIndex;

	$section_text_color = $section['section_text_color'] ?: '';
	$bg = backgroundFromSection($section); //Fixed For All Section
	$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

	$layout_type = $section['layout_type'] ?: '';
	$image_direction = $section['image_direction'] ?: '';
	$image = $section['image'] ?: '';
	$content = $section['content'] ?: '';
	// $button  = $section['button'] ?: '';

	// role="Inspiration-'.esc_attr($uniqueSectionClass).'"
	echo '<section class="image-with-content full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
	     aria-label="Image With Content - '.esc_attr($uniqueSectionClass).'"
	    '.($bg ? $bg : '').'
	    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';


	    if($layout_type == 'inContainer'){
	    	echo '<div class="container">';
		    	if($image || $content){
			    	echo '<div class="flexWrap '.esc_attr($image_direction).'">';
			    		echo '<div class="imgWrap">';
			    			echo getFocalImage($image, 'image', $pageIndex);
			    		echo '</div>'; #imgWrap
			    		echo '<div class="contentWrap">';
			    			echo HeadingFromSection($section);
			    			echo WysiwygReadMoreLess($content, 'content');
			    			echo dynamicBtnFromSection($section);
			    		echo '</div>'; #contentWrap
			    	echo '</div>'; #flexWrap
		    	}
		    echo '</div>'; #container
	    }else{
		    	if($image || $content){
			    	echo '<div class="flexWrap '.esc_attr($image_direction).' outOfContainer">';
			    		echo '<div class="imgWrap">';
			    			echo getFocalImage($image, 'image', $pageIndex);
			    		echo '</div>'; #imgWrap
			    		echo '<div class="contentWrap">';
			    			echo HeadingFromSection($section);
			    			echo WysiwygReadMoreLess($content, 'content');
			    			echo dynamicBtnFromSection($section);
			    		echo '</div>'; #contentWrap
			    	echo '</div>'; #flexWrap
		    	}
	    }

    echo '</section>'; #image-with-content

?>