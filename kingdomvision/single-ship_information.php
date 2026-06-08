<?php get_header();

$jumplink_layout = get_field('jumplink_layout');
$jumplinks  = get_field('jumplinks');
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

    $defualtPostEditor = apply_filters('the_content', get_the_content());
     if($defualtPostEditor){
        echo '<section class="shipIntroContent full-section">';
           echo '<div class="container">';
              echo '<div class="defualtPostEditor">';
                 echo $defualtPostEditor;
              echo '</div>'; #efualtPostEditor
           echo '</div>';
        echo '</section>';
     }


    // $ships_details = get_field('ships_details') ?? [];
    // if($ships_details){
    //     echo '<section class="shipDetails full-section">';
    //         echo '<div class="container">';
    //             echo '<div class="mobileTitle">';
    //                 echo '<span>Ship Specifications</span>';
    //             echo '</div>';
    //             echo '<div class="shiplisiting">';
    //                 foreach($ships_details as $key => $details){
    //                     $label = $details['label'];
    //                     $label_text = $details['label_text'];
    //                     $icons = $details['icons'];
                        
    //                     echo '<div class="shipItem '.esc_attr($label).'">';
    //                         if($icons == 'built'){
    //                             echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/built-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
    //                         }elseif($icons == 'guest'){
    //                             echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/guest-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
    //                         }elseif($icons == 'crew'){
    //                             echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/crew-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
    //                         }elseif($icons == 'bedroom'){
    //                             echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/bedroom-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
    //                         }elseif($icons == 'restaurant'){
    //                             echo '<span class="shipIcon"><img src="'.THEME_URL.'/images/shipIcons/restaurant-shipIcon.png" alt="'.esc_attr($icons).'"/></span>';
    //                         }
    //                         if($label){
    //                             echo '<p class="label">'.esc_attr($label).'</p>';
    //                         }
    //                         if($label_text){
    //                             echo '<p class="label_text">'.esc_attr($label_text).'</p>';
    //                         }
    //                     echo '</div>'; #shipItem
                        
    //                 }
    //             echo '</div>'; #shiplisiting
    //         echo '</div>'; #container
    //     echo '</section>'; #shipDetails
    // }

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

// $ship_title = get_the_title();
// $ship_url   = get_permalink();
// $ship_id    = get_the_ID();

// $image_id  = get_post_thumbnail_id();
// $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';

// $additional_properties = [];

// if( have_rows('ships_details') ) {
//     while( have_rows('ships_details') ) {
//         the_row();
//         $label = get_sub_field('label');
//         $value = get_sub_field('label_text');
//         if( $label && $value ) {
//             $additional_properties[] = [
//                 "@type" => "PropertyValue",
//                 "name" => $label,
//                 "value" => $value,
//             ];
//         }
//     }
// }

// // Build schema as TouristTrip
// $ship_schema = [
//     "@context" => "https://schema.org",
//     "@type" => "TouristTrip",
//     "name" => $ship_title,
//     "url" => $ship_url,
//     "image" => $image_url ? [$image_url] : [],
//     "identifier" => (string) $ship_id,
//     "additionalProperty" => $additional_properties,
// ];
// ?>

 <!-- <script type="application/ld+json"> -->
 <?php //echo wp_json_encode($ship_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
 <!-- </script> -->