<?php 

add_action('wp_ajax_filter_blog_posts', 'filter_blog_posts');
add_action('wp_ajax_nopriv_filter_blog_posts', 'filter_blog_posts');

function filter_blog_posts() {
    check_ajax_referer( 'filter_blog_nonce', 'nonce' );
        
    $region    = isset($_POST['region']) ? array_map('sanitize_text_field', (array) $_POST['region'] ) : [];
    $interests = isset($_POST['interests']) ? array_map('sanitize_text_field', (array) $_POST['interests'] ) : [];
    // $interests = isset($_POST['interests']) ? (array) $_POST['interests'] : [];
    $paged     = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $posts_per_page = isset($_POST['postPerPage']) ? intval($_POST['postPerPage']) : 12;

    $tax_query = [];

    // Region taxonomy
    if (!empty($region)) {
        $tax_query[] = [
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => $region,
        ];
    }

    // Interest taxonomy
    if (!empty($interests)) {
        $tax_query[] = [
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => $interests,
        ];
    }

    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'OR';
    }

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    ];

    // Add tax_query only if filters exist
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) { 
            $query->the_post();
            $post = get_the_ID();
            // echo blogStructureFromRepeator(get_the_ID(), 'Read More');
            echo blogStructureFromRepeator( rep: $post, button_text: 'Read More' );
        }
    }else{
        echo '<div class="npf"><p>No posts found.</p></div>';
    }
	
	$posts_html = ob_get_clean();
	
	wp_reset_postdata();
	
    // Check if more posts exist
    $has_more = $paged < $query->max_num_pages;

	// Return JSON
    wp_send_json([
        'posts'      => $posts_html,
        'has_more' => $has_more,
    ]);
}
