<?php

/**
 * Currency Formatting & Conversion Functions
 * OPTION A – Minimal Changes
 * Dynamic Base Currency + Dynamic XE API
 */


add_action('wp_head', 'test_price');
function test_price()
{
    if (isset($_GET['debug_currency']) && $_GET['debug_currency'] == '1') {
        $rates = get_option('cur_rate_conversion'); // ← API rates array

        echo '<div style="position:fixed;bottom:10px;right:10px;padding:10px;background:#fff;border:1px solid #000;z-index:9999;max-width:300px;">';
        echo "<h3>Currency Testing</h3>";

        echo '<strong>Base Currency:</strong> ' . cf_base_currency() . '<br>';
        echo '<strong>Current Currency:</strong> ' . cf_curr_currency() . '<br><br>';

        echo '<strong>Converted Prices:</strong><br>';
        echo '1 → ' . _currency_format(1, true) . '<br>';
        echo '2.5 → ' . _currency_format(2.5, true) . '<br>';
        echo '5 → ' . _currency_format(5, true) . '<br><br>';

        echo '<strong>API Rates (cur_rate_conversion):</strong><br>';

        if (!empty($rates) && is_array($rates)) {
            foreach ($rates as $code => $rate) {
                echo strtoupper($code) . ' : ' . $rate . '<br>';
            }
        } else {
            echo 'No XE API rates saved.<br>';
        }

        echo '</div>';
    }
}


/* -------------------------------------------------------------
   Helper: Simple formatter
------------------------------------------------------------- */
function _currency_format_two($value, $symbol = false, $decimals = 0)
{
    if ($symbol)
        return '$' . number_format_i18n($value, $decimals);
    else
        return number_format_i18n($value, $decimals);
}


/* -------------------------------------------------------------
   Get BASE currency from theme options
------------------------------------------------------------- */
function cf_base_currency()
{
    $base = get_field('base_currency', 'option');
    return $base ? strtoupper($base) : 'GBP';
}


/* -------------------------------------------------------------
   MAIN FORMATTER – UPDATED FOR OPTION A
------------------------------------------------------------- */
function _currency_format($value, $symbol = false, $decimals = 0)
{
    $base          = cf_base_currency();    // NEW dynamic base currency
    $curr_currency = cf_curr_currency();    // User-selected currency
    $rates         = get_option('cur_rate_conversion');

    // Custom override
    $custom_rate = get_custom_rates(strtolower($curr_currency));

    // Symbol logic
    $sign = '£';
    if ($curr_currency === 'AUD') $sign = 'AU$';
    elseif ($curr_currency === 'USD') $sign = '$';
    elseif ($curr_currency === 'EUR') $sign = '€';

    /* ---------------------------------------------------------
       CUSTOM RATE ALWAYS OVERRIDES
    --------------------------------------------------------- */
    if ($custom_rate && is_numeric($custom_rate)) {
        $rate_value = (float)$custom_rate;
    } else {

        /* ---------------------------------------------------------
           If BASE = current → rate = 1
        --------------------------------------------------------- */
        if ($base === $curr_currency) {
            $rate_value = 1;
        } else {
            /* ---------------------------------------------------------
               Otherwise use XE stored rate
               WHERE:
               rates[target] = targetCurrency per 1 baseCurrency
            --------------------------------------------------------- */
            $key = strtolower($curr_currency);
            if (isset($rates[$key])) {
                $rate_value = (float)$rates[$key];
            } else {
                $rate_value = 1; // fallback safety
            }
        }
    }

    // final value formatting
    $value = (float)$value;
    $converted = $value * $rate_value;

    return $symbol
        ? $sign . number_format_i18n($converted, $decimals)
        : number_format_i18n($converted, $decimals);
}


/* -------------------------------------------------------------
   Custom Rates (ACF Options)
------------------------------------------------------------- */
function get_custom_rates($curr)
{
    $price = false;
    $custom_rates = get_field('custom_pricing', 'option');

    if (
        isset($custom_rates[$curr]) &&
        $custom_rates[$curr]['available'] == 1 &&
        !empty($custom_rates[$curr]['price'])
    ) {

        $price = $custom_rates[$curr]['price'];
    }
    return $price;
}


/* -------------------------------------------------------------
   Adjustment Function (unchanged)
------------------------------------------------------------- */
function cur_adjust_format($value, $table_id, $symbol = false, $decimals = 0)
{
    $curr_currency   = cf_curr_currency();
    $curr_key        = strtolower($curr_currency);
    $custom_rate     = get_custom_rates($curr_key);
    $rate_conversion = get_option('cur_rate_conversion');
    $adjustment      = get_field('adjustment_%', $table_id);
    $rate_value      = 1;
    $sign            = '£';

    if ($curr_currency == 'AUD') {
        $sign = 'AU$';
        $rt = $custom_rate ? $custom_rate : ($rate_conversion['aud'] ?? 1);
        $rate_value = round((float)$rt, 4);
    } elseif ($curr_currency == 'USD') {
        $sign = '$';
        $rt = $custom_rate ? $custom_rate : ($rate_conversion['usd'] ?? 1);
        $rate_value = round((float)$rt, 4);
    } elseif ($curr_currency == 'EUR') {
        $sign = '€';
        $rt = $custom_rate ? $custom_rate : ($rate_conversion['eur'] ?? 1);
        $rate_value = round((float)$rt, 4);
    }

    if (is_array($adjustment) && array_key_exists($curr_key, $adjustment)) {
        if (is_numeric($adjustment[$curr_key]) && $adjustment[$curr_key] > 0) {
            $rate_value += ($rate_value * ((float)$adjustment[$curr_key] / 100));
        }
    }

    $value = $rate_value * $value;

    return $symbol
        ? $sign . number_format_i18n($value, $decimals)
        : number_format_i18n($value, $decimals);
}


/* -------------------------------------------------------------
   Symbol Only (unchanged)
------------------------------------------------------------- */
function _currency_sign()
{
    $curr_currency = cf_curr_currency();
    $sign = '£';

    if ($curr_currency == 'AUD') $sign = 'AU$';
    elseif ($curr_currency == 'USD') $sign = '$';
    elseif ($curr_currency == 'EUR') $sign = '€';

    return $sign;
}


/* -------------------------------------------------------------
   Shortcode (unchanged)
------------------------------------------------------------- */
add_shortcode('price', 'price_shortcode');
function price_shortcode($atts)
{
    $atts = shortcode_atts(['value' => ''], $atts);

    return '<span class="price">' . _currency_format($atts['value'], true) . '</span>';
}


/* -------------------------------------------------------------
   Init (Cookie + Cron)
------------------------------------------------------------- */
add_action('init', 'cf_init');
function cf_init()
{
    // if (!headers_sent()) {
    //     setcookie('cc_format', cf_curr_currency(), time() + (86400 * 30), "/");
    // }

    if (!wp_next_scheduled('wpb_custom_cron')) {
        wp_schedule_event(time(), 'daily', 'wpb_custom_cron');
    }
}


/* -------------------------------------------------------------
   UPDATED XE CRON (Dynamic Base)
------------------------------------------------------------- */
add_action('wpb_custom_cron', 'wpb_custom_cron_func');
function wpb_custom_cron_func()
{
    $base = cf_base_currency();   // ⭐ NEW dynamic base currency
    $all  = ['GBP', 'USD', 'AUD', 'EUR'];

    // build target list except base
    $targets = array_filter($all, fn($c) => $c !== $base);
    $to      = implode(',', $targets);

    // dynamic API URL
    $url = "https://xecdapi.xe.com/v1/convert_from.json/?from={$base}&to={$to}&amount=1";

    $insertXeCurrencyApi = get_field('insert_xe_currency_api' , 'option');
    if($insertXeCurrencyApi){
        $response = wp_remote_get($url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(
                    $insertXeCurrencyApi
                )
            ]
        ]);
    }

    $body = wp_remote_retrieve_body($response);
    if (!$body) return;

    $json = json_decode($body);

    $rate_value = [];

    foreach ($json->to as $rate_api) {
        $rate_value[strtolower($rate_api->quotecurrency)] = round($rate_api->mid, 6);
    }

    // base always = 1
    $rate_value[strtolower($base)] = 1;

    update_option('cur_rate_conversion', $rate_value);
}


/* -------------------------------------------------------------
   Currency Detection
------------------------------------------------------------- */
function cf_curr_currency()
{
    if (!empty($_GET['currency'])) {
        return trim(strtoupper($_GET['currency']));
    }
    if (!empty($_COOKIE['cc_format'])) {
        return trim(strtoupper($_COOKIE['cc_format']));
    }
    return trim(strtoupper(cf_geo_location()));
}


/* -------------------------------------------------------------
   GEO fallback
------------------------------------------------------------- */
if (!function_exists('cf_geo_location')) {
    function cf_geo_location()
    {
        return 'GBP';
    }
}


// Run live update when base currency is changed in theme options
add_action('acf/save_post', function ($post_id) {

    // Only trigger on your Theme Options page
    if ($post_id !== 'options' && $post_id !== 'theme-options') {
        return;
    }

    // Only run when base currency is updated
    $base = get_field('base_currency', 'option');
    if (!$base) {
        return;
    }

    // Call your cron function immediately
    wpb_custom_cron_func();
});
