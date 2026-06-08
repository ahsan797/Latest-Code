<?php

add_shortcode('cst-breadcrumbs', 'codex_generate_custom_breadcrumbs');

function codex_generate_custom_breadcrumbs($atts): string {
    ob_start();

    // Safe defaults with named unpacking (PHP 7.4+)
    $atts = shortcode_atts([
        'id'   => get_the_ID(),
        'slug' => false,
    ], $atts);

    $id   = $atts['id'];
    $slug = filter_var($atts['slug'], FILTER_VALIDATE_BOOLEAN); // sanitise boolean

    if (!empty($id)) :
        echo '<nav class="cst-breadcrumbs" role="navigation" aria-label="Breadcrumbs">';
            echo '<ul>';
                echo '<li class="home_bc">';
                    echo '<a href="'.esc_url(home_url('/')).'" aria-label="Home">';
                        echo '<i class="fa fa-home" aria-hidden="true"></i> Home';
                    echo '</a>';
                echo '</li>';
                if (have_rows('custom_breadcrumb', $id)) :
                    while (have_rows('custom_breadcrumb', $id)) : the_row();
                        $value = get_sub_field('custom_breadcrumb_link');

                        if (!is_a($value, 'WP_Post')) {
                            continue;
                        }

                        $post_id    = $value->ID;
                        $title      = esc_html($value->post_title);
                        $slug_title = esc_html(ucfirst(str_replace('-', ' ', $value->post_name)));
                        $link       = esc_url(get_permalink($post_id));

                        echo '<li>';
                            echo '<a href="'.esc_url($link).'">';
                                echo $slug ? $slug_title : $title;
                            echo '</a>';
                        echo '</li>';
                    endwhile;
                endif;

                echo '<li><span aria-current="page">'.esc_html(get_the_title($id)).'</span></li>';
            echo '</ul>';
        echo '</nav>';
    endif;

    return ob_get_clean();
}

//------------------ACF Schema Field Maping----------------
function acf_load_select_schema_city_field_choices($field) {
    global $post;
    // reset choices
    $field['choices'] = array();

    // if has rows
    if (have_rows('location_data', 'option')) {

        if (!empty(get_post_meta($post->ID, 'select_schema_city', true))) $field['default_value'] = array(
            get_post_meta($post->ID, 'select_schema_city', true)
        );

        $field['choices'][''] = 'Select Location';

        // while has rows
        while (have_rows('location_data', 'option')) {
            // instantiate row
            the_row();

            // vars
            $value = str_replace(' ', '_', get_sub_field('schema_title'));
            $value = sanitize_key($value);
            $label = get_sub_field('schema_title');

            // append to choices
            $field['choices'][$value] = $label;
        }
    }

    // return the field
    return $field;
}

add_filter('acf/load_field/name=select_schema_city', 'acf_load_select_schema_city_field_choices', 999);

// Add scripts to wp_head()
function location_schema() {
    //<!----------------Schema Start------------->
    $page_id = get_the_ID();

    $href_url = get_field('select_schema_city', $page_id);
    if (empty($href_url)) {
        $href_url = array(
            '0' => 'croatia'
        );
    }

    if (have_rows('location_data', 'option')) :

        while (have_rows('location_data', 'option')) :
            the_row();
            $value = str_replace(' ', '_', get_sub_field('schema_title'));
            $values = strtolower($value);
            if (!in_array($values, $href_url))
                continue;
            printf('<script type="application/ld+json">{"@context":"http://schema.org","@type":"Place","name":"%s","publicAccess":true,"isAccessibleForFree":true,"sameAs":["%s","%s"]}</script>', get_sub_field('schema_title'), get_sub_field('schema_wikipedia_url'), get_sub_field('schema_wikidata_url'));

        endwhile;
    endif;

    //  BreadCrumb Schema==================
    $id = get_the_ID();
    $breadcrumbs = get_field('custom_breadcrumb', $id);
    if (!empty($breadcrumbs)) {
        $schema = '<script type="application/ld+json" cst-crumb-schema>{"@context": "https://schema.org","@type": "BreadcrumbList","itemListElement": [';
        $i = 1;
        if (!home_url()) {
            foreach ($breadcrumbs as $track) {
                $value = $track['custom_breadcrumb_link'];
                $post_id = $value->ID;
                $breadcrumb_title = $value->post_title;
                // $breadcrumb_slug = ucfirst(str_replace('-', ' ', $value->post_name));
                $page_link = get_permalink($post_id);

                $schema .= '{"@type": "ListItem","position": ' . $i . ',"name": "' . $breadcrumb_title . '","item": "' . $page_link . '"},';
                $i++;
            }
        }
        $schema .= '{"@type": "ListItem","position": ' . $i . ',"name": "' . get_the_title($id) . '","item": "' . get_permalink($id) . '"}';
        $schema .= ']}</script>';
        echo $schema;
    }

    //  BreadCrumb Schema================== END

    //<!----------------Schema END--------------->

}

add_action('wp_head', 'location_schema');