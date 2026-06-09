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
                        echo '<h1 class="heading">'.esc_html($object->post_title).'</h1>';
                    }elseif($heading_tags == 'h3'){
                        echo '<h3 class="heading">'.esc_html($object->post_title).'</h3>';
                    }elseif($heading_tags == 'h4'){
                        echo '<h4 class="heading">'.esc_html($object->post_title).'</h4>';
                    }elseif($heading_tags == 'h5'){
                        echo '<h5 class="heading">'.esc_html($object->post_title).'</h5>';
                    }elseif($heading_tags == 'h6'){
                        echo '<h6 class="heading">'.esc_html($object->post_title).'</h6>';
                    }else{
                        echo '<h2 class="heading">'.esc_html($object->post_title).'</h2>';
                    }
                    if($rating){
                        echo '<div class="starWrapper">';
                            for ($i = 0; $i < $rating; $i++) {
                                echo '<i class="fa-brand fa-star"></i>';
                            }
                        echo '</div>'; #starWrapper
                    }

                    if($hotel_description){
                        echo '<div class="hotelDescription">';
                            echo wp_kses_post($hotel_description);
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
?>