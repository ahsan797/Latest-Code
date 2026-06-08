<?php

// Product Post Type
add_action('init', 'products_post_type', 0);
function products_post_type() {

    add_theme_support('post-thumbnails');

    register_post_type('products', array(

        'labels' => array(
            'name' => __('Products'),
            'singular_name' => __('Product'),
            'edit_item'   => __('Edit Product'),
            'view_item' => __('View Product')
        ),

        'supports' => array(
            'title',
            // 'editor',
            'thumbnail',
            'revisions'
        ),

        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'query_var' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => false, 'with_front' => false),
        'menu_icon' => 'dashicons-cart'
    ));

    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name' => _x('Product Categories', 'taxonomy general name', 'textdomain'),
        'singular_name' => _x('Product Category', 'taxonomy singular name', 'textdomain'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'product-category', 'with_front' => false, 'hierarchical' => true),
    );

    register_taxonomy('product_category', array(
        'products'
    ), $args);

    flush_rewrite_rules();
}


// Ship Information Post type
add_action('init', 'ship_information_post_type');
function ship_information_post_type()
{
    register_post_type('ship_information', array(
        'labels' => array(
            'name' => __('Ship Information'),
            'singular_name' => __('Ship Information'),
            'edit_item' => __('Edit Ship'),
            'view_item' => __('View Ship')
        ),
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-performance'
    ));
}

// Hotel Information Post type
add_action('init', 'hotel_information_post_type');

function hotel_information_post_type()
{
    register_post_type('hotel_information', array(
        'labels' => array(
            'name' => __('Hotel Information'),
            'singular_name' => __('Hotel Information'),
            'edit_item' => __('Edit Hotel'),
            'view_item' => __('View Hotel')
        ),
        'supports' => array(
            'title',
            //'editor',
            'thumbnail'
        ),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-performance'
    ));
}


// Experiences Post Type
add_action('init', 'experiences_post_type', 0);
function experiences_post_type()
{

    add_theme_support('post-thumbnails');

    register_post_type('experiences', array(

        'labels' => array(
            'name' => __('Experiences'),
            'singular_name' => __('Experiences'),
            'edit_item' => __('Edit Experience'),
            'view_item' => __('View Experience')
        ),

        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ),

        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'query_var' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => false, 'with_front' => false),
        'menu_icon' => 'dashicons-star-filled'
    ));

    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name' => _x('Experiences Categories', 'taxonomy general name', 'textdomain'),
        'singular_name' => _x('Experiences Category', 'taxonomy singular name', 'textdomain'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'experiences-category', 'with_front' => false, 'hierarchical' => true),
    );

    register_taxonomy('experiences_category', array(
        'experiences'
    ), $args);

    flush_rewrite_rules();
}