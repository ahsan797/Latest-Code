<?php 

function na_remove_slug( $post_link, $post, $leavename ) {
    if ( !in_array($post->post_type, array('products', 'ship_information')) || 'publish' != $post->post_status ) {
        return $post_link;
    }

    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    return $post_link;
}
add_filter( 'post_type_link', 'na_remove_slug', 10, 3 );


function na_parse_request( $query ) {
    if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }

    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'products', 'ship_information', 'page' ) );
    }
}
add_action( 'pre_get_posts', 'na_parse_request' );

function filter_post_link($permalink, $post) {
    if ($post->post_type != 'post')
        return $permalink;
    return 'blog'.$permalink;
}
add_filter('pre_post_link', 'filter_post_link', 10, 2);


add_action( 'generate_rewrite_rules', 'add_blog_rewrites' );
function add_blog_rewrites( $wp_rewrite ) {
    $wp_rewrite->rules = array(
        'blog/([^/]+)/?$' => 'index.php?name=$matches[1]',
        'blog/[^/]+/attachment/([^/]+)/?$' => 'index.php?attachment=$matches[1]',
        'blog/[^/]+/attachment/([^/]+)/trackback/?$' => 'index.php?attachment=$matches[1]&tb=1',
        'blog/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
        'blog/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
        'blog/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$' => 'index.php?attachment=$matches[1]&cpage=$matches[2]',
        'blog/([^/]+)/trackback/?$' => 'index.php?name=$matches[1]&tb=1',
        'blog/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?name=$matches[1]&feed=$matches[2]',
        'blog/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?name=$matches[1]&feed=$matches[2]',
        'blog/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?name=$matches[1]&paged=$matches[2]',
        'blog/([^/]+)/comment-page-([0-9]{1,})/?$' => 'index.php?name=$matches[1]&cpage=$matches[2]',
        'blog/([^/]+)(/[0-9]+)?/?$' => 'index.php?name=$matches[1]&page=$matches[2]',
        'blog/[^/]+/([^/]+)/?$' => 'index.php?attachment=$matches[1]',
        'blog/[^/]+/([^/]+)/trackback/?$' => 'index.php?attachment=$matches[1]&tb=1',
        'blog/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
        'blog/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
        'blog/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$' => 'index.php?attachment=$matches[1]&cpage=$matches[2]',
    ) + $wp_rewrite->rules;
}

/** Change Post type link For Product -- Add selected Page Slug in URL */
add_filter('post_type_link', 'product_remove_cpt_slug_kv', 10, 3);
function product_remove_cpt_slug_kv($post_link, $post, $leavename)
{
    if ('products' != $post->post_type || 'publish' != $post->post_status) {
        return $post_link;
    }
    $prod_ppg = get_field('parent_page_for_url', $post->ID, true);
    if (isset($prod_ppg) && !empty($prod_ppg)) {
        $post_link = str_replace('/' . $post->post_name . '/', '/' . $prod_ppg->post_name . '/' . $post->post_name . '/', $post_link);
    }
    return $post_link;
}

function hotel_remove_cpt_slug($post_link, $post, $leavename)
{
    if ('hotel_information' != $post->post_type || 'publish' != $post->post_status) {
        return $post_link;
    }

    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);
    return $post_link;
}

add_filter('post_type_link', 'hotel_remove_cpt_slug', 10, 3);


function kv_parse_request_trick($query)
{

    // Only noop the main query
    if (!$query->is_main_query()) return;

    // Only noop our very specific rewrite rule match
    if (2 != count($query->query) || !isset($query->query['page'])) {
        return;
    }

    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if (!empty($query->query['name'])) {
        $query->set('post_type', array(
            'post',
            'page',
            'hotel_information',
            'products',
            'ship_information',
        ));
    }
}

add_action('pre_get_posts', 'kv_parse_request_trick');