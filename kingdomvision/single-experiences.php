<?php 
get_header();

// Jumplink & Breadcrumb
$jumplinks   = get_field('jumplinks');
$custom_breadcrumb = get_field('custom_breadcrumb');


echo '<main class="content-wrapper full-section">';

	// Top Banner
	get_template_part('topBannerUTC');

	if (!is_front_page()){
		echo '<section class="breadcrumb-wrapper full-section"
			role="Breadcrumb" 
			aria-label="Breadcrumb">';
			echo '<div class="container">'; 
				echo do_shortcode('[cst-breadcrumbs]');
			echo '</div>';
		echo '</section>';
	}


	if ($jumplinks){
		echo jumplinksCode($jumplinks);
	}

	// Product Builder
	$product_builder = get_field('product_page_builder');
	if (!empty($product_builder) && is_array($product_builder)){
	    $sectionIndex = 0;
	    foreach ($product_builder as $pageIndex => $section) {
	        $sectionIndex++;
	        $layout = $section['acf_fc_layout'];
	        $pageIndex = $layout;
	        $template = locate_template("sections/products/pro-{$layout}.php");

	        if ($template) {
	            $currenSectionIndex = $sectionIndex;
	            include $template;
	        }
	    }

	}else {
	    echo '<div class="container" style="text-align:center;">';
	        echo '<h2 style="margin:20px 0;display:inline-block;">Fields Not Found</h2>';
	    echo '</div>';
	}

echo '</main>';

get_footer();
?>