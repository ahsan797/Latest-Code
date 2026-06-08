<?php get_header();
/* 
 * The default page template
 */

$jumplink_layout = get_field('jumplink_layout');
$jumplink_title = get_field('jumplink_title');
$jumplinks  = get_field('jumplinks');
$custom_breadcrumb = get_field('custom_breadcrumb');


echo '<main class="content-wrapper full-section">';
// Top Banner
get_template_part('topBannerUTC');

if ($jumplinks) {
    echo jumplinksCode($jumplinks, $jumplink_layout, $jumplink_title);
}

if (!is_front_page()) {
    echo '<section class="breadcrumb-wrapper full-section"
            aria-label="Breadcrumb">';
    echo '<div class="container">';
    echo do_shortcode('[cst-breadcrumbs]');
    echo '</div>';
    echo '</section>';
}

// Page Builder
$page_builder = get_field('page_builder');

if (!empty($page_builder) && is_array($page_builder)) {
    $sectionIndex = 0;
    foreach ($page_builder as $pageIndex => $section) :
        $sectionIndex++;
        $layout = $section['acf_fc_layout'];
        
        $pageIndex = $layout;

        $template = locate_template("sections/pages/{$layout}.php");

        if ($template) {
            $currenSectionIndex = $sectionIndex;
            include $template;
        }

    endforeach;
} else {
    echo '<div class="container" style="text-align:center;">';
    echo '<h2 style="margin:20px 0;display:inline-block;">Fields Not Found</h2>';
    echo '</div>';
}
echo '</main>';


get_footer();
