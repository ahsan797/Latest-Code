<?php
/**
 * Custom Login URL Protection - Strict 404 & No Redirect Parameters
 */

define('CUSTOM_LOGIN_SLUG', 'ufg-cms');

/**
 * 1. Setup Rewrite Rule
 */
add_action('init', function () {
    add_rewrite_rule(
        '^' . CUSTOM_LOGIN_SLUG . '/?$',
        'index.php?custom_login=1',
        'top'
    );
}, 1);

/**
 * 2. Register Query Var
 */
add_filter('query_vars', function ($vars) {
    $vars[] = 'custom_login';
    return $vars;
});

/**
 * 3. Route Custom Login URL Safely & Clean URL (Stops Redirect Parameter)
 */
add_action('parse_request', function ($wp) {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Check if accessing the custom slug
    if (isset($wp->query_vars['custom_login']) || strpos($request_uri, '/' . CUSTOM_LOGIN_SLUG) !== false) {
        
        // AGAR URL ME REDIRECT_TO LAGA HAI AUR USER LOGGED IN NAHI HAI, TO CLEAN URL PAR REDIRECT KAREIN
        if (isset($_GET['redirect_to']) && !is_user_logged_in()) {
            wp_safe_redirect(home_url('/' . CUSTOM_LOGIN_SLUG . '/'));
            exit;
        }

        // Setup internal environment variables safely
        $_SERVER['SCRIPT_NAME'] = '/wp-login.php';
        $_SERVER['PHP_SELF']    = '/wp-login.php';
        
        define('UFG_CUSTOM_LOGIN_ALLOWED', true);

        require_once ABSPATH . 'wp-login.php';
        exit;
    }
}, 1);

/**
 * 4. Block Direct wp-login.php Access
 */
add_action('init', function () {
    $current_script = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $request_uri    = $_SERVER['REQUEST_URI'] ?? '';

    if ($current_script === 'wp-login.php' && !defined('UFG_CUSTOM_LOGIN_ALLOWED')) {
        
        if (strpos($request_uri, CUSTOM_LOGIN_SLUG) !== false) {
            return; // Allow internal routing
        }

        // Show official Theme 404
        status_header(404);
        nocache_headers();

        global $wp_query;
        $wp_query->set_404();

        if ($template = get_404_template()) {
            include $template;
        } else {
            wp_die('404 Not Found', '', array('response' => 404));
        }
        exit;
    }
}, 2);

/**
 * 5. Block wp-admin For Guests & Force Theme 404 (Strict Fix)
 */
add_action('admin_init', function () {
    if (
        !is_user_logged_in()
        && !wp_doing_ajax()
        && !defined('DOING_CRON')
    ) {
        // Guest agar wp-admin par aaye to login link par bhejne ke bajaye furan 404 header dein
        status_header(404);
        nocache_headers();

        global $wp_query;
        $wp_query->set_404();

        if ($template = get_404_template()) {
            include $template;
        } else {
            wp_die('404 Not Found', '', array('response' => 404));
        }
        exit;
    }
}, 1);

/**
 * 6. Global Login URL Filters
 */
add_filter('login_url', function ($login_url, $redirect, $force_reauth) {
    if (defined('UFG_CUSTOM_LOGIN_ALLOWED')) {
        return $login_url;
    }

    return home_url('/' . CUSTOM_LOGIN_SLUG . '/');
}, 999, 3);

add_filter('lostpassword_url', function ($url, $redirect) {
    return home_url('/' . CUSTOM_LOGIN_SLUG . '/?action=lostpassword');
}, 999, 2);

add_filter('logout_url', function ($logout_url, $redirect) {
    return wp_nonce_url(
        home_url('/' . CUSTOM_LOGIN_SLUG . '/?action=logout'),
        'log-out'
    );
}, 999, 2);