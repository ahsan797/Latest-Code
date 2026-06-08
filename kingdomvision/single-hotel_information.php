<?php get_header();

$object = get_queried_object();

$jumplink_layout = get_field('jumplink_layout');
$jumplinks  = get_field('jumplinks');
$custom_breadcrumb = get_field('custom_breadcrumb');


    echo '<main class="content-wrapper full-section">';

        // Top Banner
        get_template_part('topHotelBanner');

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



        $hotel_details = get_field('hotel_details') ?? [];
        $heading_tags = $hotel_details['heading_tags'];
        $sub_headiing = $hotel_details['sub_headiing'];
        $sectionid = $hotel_details['sectionid'];
        $rating = $hotel_details['rating'];
        $hotel_description = $hotel_details['hotel_description'];
        if($heading_tags || $sub_headiing || $rating || $hotel_description){
            echo '<section id="'. $sectionid .'" class="hotelDetails full-section">';
                echo '<div class="container">';
                    if($sub_headiing){
                        echo '<span>'.$sub_headiing.'</span>';
                    }
                    if($heading_tags == 'h1'){
                        echo '<h1 class="heading">'.$object->post_title.'</h1>';
                    }elseif($heading_tags == 'h3'){
                        echo '<h3 class="heading">'.$object->post_title.'</h3>';
                    }elseif($heading_tags == 'h4'){
                        echo '<h4 class="heading">'.$object->post_title.'</h4>';
                    }elseif($heading_tags == 'h5'){
                        echo '<h5 class="heading">'.$object->post_title.'</h5>';
                    }elseif($heading_tags == 'h6'){
                        echo '<h6 class="heading">'.$object->post_title.'</h6>';
                    }else{
                        echo '<h2 class="heading">'.$object->post_title.'</h2>';
                    }
                    if($rating){
                        echo '<div class="starWrapper">';
                            if($rating == 1){
                                echo '<i class="fa-solid fa-star"></i>';
                            }elseif($rating == 2){
                                echo '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
                            }elseif($rating == 3){
                                echo '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
                            }elseif($rating == 4){
                                echo '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
                            }elseif($rating == 5){
                                echo '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
                            }
                        echo '</div>'; #starWrapper
                    }

                    if($hotel_description){
                        echo '<div class="hotelDescription">';
                            echo $hotel_description;
                        echo '</div>'; #hotelDescription
                    }
                echo '</div>'; #container
            echo '</section>'; #hotelDetails
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

$hotel_title = get_the_title();
$hotel_url   = get_permalink();
$hotel_id    = get_the_ID();

$image_id  = get_post_thumbnail_id();
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';

$brand_name = 'Unforgettable Travel Company'; 

$additional_properties = [];

if( have_rows('hotel_details') ) {
    while( have_rows('hotel_details') ) {
        the_row();
        $sub_heading = get_sub_field('sub_heading');
        $rating      = get_sub_field('rating');
        $description = get_sub_field('hotel_description');

        if( $sub_heading || $rating || $description ) {
            $value_parts = [];
            if( $rating ) {
                $value_parts[] = "Rating: " . $rating;
            }
            if( $description ) {
                $clean_description = wp_strip_all_tags( $description );
                $value_parts[] = "Description: " . $clean_description;
            }
            $value_text = implode("; ", $value_parts);

            $additional_properties[] = [
                "@type" => "PropertyValue",
                "name"  => $sub_heading ?: "Detail",
                "value" => $value_text,
            ];
        }
    }
}

// Build schema as Hotel with brand
$hotel_schema = [
    "@context" => "https://schema.org",
    "@type" => "Hotel",
    "name" => $hotel_title,
    "url" => $hotel_url,
    "image" => $image_url ? [$image_url] : [],
    "identifier" => (string) $hotel_id,
    "brand" => [
        "@type" => "Organization",
        "name" => $brand_name,
    ],
    "additionalProperty" => $additional_properties,
];
?>

<script type="application/ld+json">
<?php echo wp_json_encode($hotel_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
