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

// Product basic data
$product_title = get_the_title();
$product_url   = get_permalink();

// Featured image
$image_id  = get_post_thumbnail_id();
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';

// ACF group field: product
$product_group = get_field('product');
$description   = $product_group['short_description'] ?? '';

// Price logic (sale > regular)
$regular_price = $product_group['regular_price'] ?? '';
$sale_price    = $product_group['sale_price'] ?? '';
$price         = $sale_price ?: $regular_price;

// Duration field (text like: "7 Days / 6 Nights")
$duration_raw = get_field('duration');
$duration_value = null;

if ($duration_raw && preg_match('/(\d+)\s*(day|days|night|nights|week|weeks)/i', $duration_raw, $matches)) {
    $duration_value = (int) $matches[1];
}

// Brand (static)
$brand_name = 'Unforgettable Travel Company';

// Build the offer array
$offer = [
    "@type" => "Offer",
    "url" => $product_url,
    "priceCurrency" => "GBP",
    "price" => (float) $price,
    "priceValidUntil" => date('Y-12-31'),
    "availability" => "https://schema.org/InStock",
    "itemCondition" => "https://schema.org/NewCondition",
    "seller" => [
        "@type" => "Organization",
        "name" => $brand_name,
    ],
];

// Add duration if found
if ($duration_value) {
    $offer['eligibleDuration'] = [
        "@type" => "QuantitativeValue",
        "value" => $duration_value,
        "unitCode" => "DAY",
    ];
}

// Build full product schema
$product_schema = [
    "@context" => "https://schema.org",
    "@type" => "Product",
    "name" => $product_title,
    "sku" => (string) get_the_ID(),
    "image" => $image_url ? [$image_url] : [],
    "description" => wp_strip_all_tags($description),
    "category" => "Travel Package",
    "brand" => [
        "@type" => "Brand",
        "name" => $brand_name,
    ],
    "offers" => $offer,
];
?>

<script type="application/ld+json">
<?php echo wp_json_encode($product_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

