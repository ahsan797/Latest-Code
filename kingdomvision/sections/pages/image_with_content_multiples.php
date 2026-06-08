<?php

	// Unique Section Class
	$uniqueSectionClass = 'section-'.$currenSectionIndex;

	$section_text_color = $section['section_text_color'] ?: '';
	$bg = backgroundFromSection($section); //Fixed For All Section
	$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

	$multiples = $section['multiples'];

	$layout_type = $section['layout_type'] ?? 'inContainer';
	$image_direction = $section['image_direction'] ?? '';
	$image = $section['image'] ?? '';
	$content = $section['content'] ?? '';


	echo '<section class="image-with-content full-section '.esc_attr($section_text_color).' '.esc_attr($uniqueSectionClass).'" 
	    aria-label="Image With Content - '.esc_attr($uniqueSectionClass).'"
	    '.($bg ? $bg : '').'
	    '.($section_id ? 'id="'.esc_attr($section_id).'"' : '').' >';

	    if($multiples){
	    	echo '<div class="multipleWrap">';
	    		foreach ($multiples as $key => $value) {
	    			$layout_type = $value['layout_type'] ?: '';
					$image_direction = $value['image_direction'] ?: '';
					$image = $value['image'] ?: '';
					$content = $value['content'] ?: '';

					if($layout_type == 'inContainer'){
				    	echo '<div class="container">';
					    	if($image || $content){
						    	echo '<div class="flexWrap '.esc_attr($image_direction).'">';
						    		echo '<div class="imgWrap">';
						    			echo getFocalImage($image, 'image', $pageIndex);
						    		echo '</div>'; #imgWrap
						    		echo '<div class="contentWrap">';
						    			echo HeadingFromSection($value);
						    			echo WysiwygReadMoreLess($content, 'content');
						    			echo dynamicBtnFromSection($value);
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
						    			echo HeadingFromSection($value);
						    			echo WysiwygReadMoreLess($content, 'content');
						    			echo dynamicBtnFromSection($value);
						    		echo '</div>'; #contentWrap
						    	echo '</div>'; #flexWrap
					    	}
				    }


	    		}
	    	echo '</div>'; #multipleWrap
	    }

    echo '</section>'; #image-with-content

?>