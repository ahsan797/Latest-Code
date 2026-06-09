<?php

// Register "Reviews" Custom Post Type
add_action('init', 'reviews_post_type');
function reviews_post_type() {
    register_post_type('reviews', array(
        'labels' => array(
            'name' => __('Reviews', 'kingdomVision'),
            'singular_name' => __('Review', 'kingdomVision')
        ),
        'supports' => array('title', 'editor'),
        'public' => false,
        'hierarchical' => false,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-testimonial'
    ));
}

if (!current_user_can('manage_options')) wp_die(__('Forbidden'));

// Admin Menu: Fetch Reviews Button
add_action('admin_menu', 'add_reviews_submenu');
function add_reviews_submenu() {
    add_submenu_page(
        'edit.php?post_type=reviews',
        __('Fetch Reviews', 'kingdomVision'),
        __('Fetch Reviews', 'kingdomVision'),
        'manage_options',
        'wnm_fund_set',
        'render_review_fetch_page'
    );
}

// Render Feefo Review Fetch Button
function render_review_fetch_page() {
    ?>
    <div class="wrap">
        <h2><?php _e('Fetch New Reviews from Feefo', 'kingdomVision'); ?></h2>
        <form action="<?php echo esc_url(site_url('/feefo-review.php')); ?>" method="get" id="form1">
            <button class="button button-primary" type="submit" form="form1">
                <?php _e('Fetch Reviews', 'kingdomVision'); ?>
            </button>
        </form>
    </div>
    <?php
}

// Add Stars Meta Box in Review Edit Screen
add_action('add_meta_boxes', 'reviews_add_star_metabox');
function reviews_add_star_metabox() {
    add_meta_box(
        'review_stars_box',             // ID
        __('Review Rating (Stars)', 'kingdomVision'),  // Title
        'reviews_star_metabox_callback', // Callback
        'reviews',                       // Post type
        'side',                          // Context: side, normal, advanced
        'default'                        // Priority
    );
}

// Meta Box Content
function reviews_star_metabox_callback($post) {
    $stars = get_post_meta($post->ID, 'stars', true);
    wp_nonce_field('reviews_stars_nonce_action', 'reviews_stars_nonce');
    ?>
    <label for="review_stars"><?php _e('Star Rating (1–5)', 'kingdomVision'); ?></label><br>
    <select name="review_stars" id="review_stars" style="width:100%;">
        <?php for ($i = 1; $i <= 5; $i++) : ?>
            <option value="<?php echo $i; ?>" <?php selected($stars, $i); ?>>
                <?php echo $i; ?> Star<?php echo ($i > 1) ? 's' : ''; ?>
            </option>
        <?php endfor; ?>
    </select>
    <?php
}

// Add new column to Reviews admin list
add_filter('manage_reviews_posts_columns', 'add_stars_column_to_reviews');
function add_stars_column_to_reviews($columns) {
    // Insert "Stars" column after Title
    $new = [];
    foreach ($columns as $key => $value) {
        $new[$key] = $value;
        if ($key === 'title') {
            $new['stars'] = __('Stars', 'kingdomVision');
        }
    }
    return $new;
}

// Show star ratings inside the new column
add_action('manage_reviews_posts_custom_column', 'display_stars_in_admin_column', 10, 2);
function display_stars_in_admin_column($column, $post_id) {
    if ($column === 'stars') {
        $stars = get_post_meta($post_id, 'stars', true);

        if ($stars) {
            // Show filled + empty stars
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $stars 
                    ? '<span style="color:#f5a623;">&#9733;</span>' 
                    : '<span style="color:#ddd;">&#9733;</span>';
            }
        } else {
            echo '<span style="color:#aaa;">–</span>'; // no rating
        }
    }
}

// Reviews Shortcode: [reviews]
add_shortcode('reviews', 'codex_reviews');
function codex_reviews() {
    ob_start();

    $args = array(
        'post_type'      => 'reviews',
        'posts_per_page' => 15
    );
    $loop = new WP_Query($args);
    if ($loop->have_posts()){
        echo '<div class="reviewsCarouselWrapper">';
            while ($loop->have_posts()) { 
                $loop->the_post();

                $rating      = get_post_meta(get_the_ID(), 'stars', true);
                $review_url  = esc_url(get_post_meta(get_the_ID(), 'review_url', true));
                $author_name = esc_html(get_post_meta(get_the_ID(), 'author_name', true));
                $title       = esc_html(get_the_title());
                $excerpt     = wp_strip_all_tags(get_the_content());
                $excerpt     = esc_html(mb_strimwidth($excerpt, 0, 100, '...'));
                $time_ago    = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';

                echo '<div class="item" role="group" aria-label="Customer review">';
                    echo '<div class="review">';
                        echo '<a href="'.$review_url.'" target="_blank" rel="noopener noreferrer" class="coverAnchor" aria-label="'.$author_name.'"></a>';
                        echo '<p class="excerpt">'.$excerpt.'</p>';
                        echo '<p class="customer-name">';
                            echo $author_name; 
                        echo '</p>';
                    echo '</div>';
                echo '</div>'; #item

            }
        echo '</div>'; #reviewsCarouselWrapper
    }

    wp_reset_postdata();
    return ob_get_clean();
}