jQuery(function ($) {
    // console.log('Currency script loaded');
    if (typeof ccdata === 'undefined') {
        console.error('Currency data missing');
        return;
    }

    window.CURRENCY = ccdata.base || 'USD';


    async function cfGeoLocation() {

        let isoCode = ccdata.cf_country || 'US';

        try {
            const res = await fetch('https://ipapi.co/json/');
            const data = await res.json();

            if (data && data.country_code) {
                isoCode = data.country_code;
            }
        } catch (e) {
            isoCode = 'US';
        }
        console.log('Detected ISO code:', isoCode);


        // Currency mapping (same as PHP logic)
        if (isoCode === 'AU') return 'AUD';
        if (isoCode === 'GB') return 'GBP';
        if (isoCode === 'US') return 'USD'; // ⭐ FIX
        if (isEuroCountry(isoCode)) return 'EUR';

        return ccdata.base || 'USD';
    }
    

    // Helper for EUR (since "EU" doesn't exist)
    function isEuroCountry(code) {
        const euroCountries = [
            'FR', 'DE', 'ES', 'IT', 'NL', 'BE', 'PT', 'IE', 'AT', 'FI', 'GR', 'LU', 'SI', 'SK', 'EE', 'LV', 'LT', 'CY', 'MT'
        ];
        return euroCountries.includes(code);
    }

    function currencyFormat(value, symbol = false, decimals = 0, curr = null) {
        curr = curr || window.CURRENCY;
        let rates = ccdata.rates || {};
        let custom = ccdata.custom || {};
        let rate_value = 1.0;
        let sign = '$';
        let currKey = (curr || '').toLowerCase();
        let custom_rate = false;
        if (
            custom[currKey] &&
            custom[currKey].available == 1 &&
            custom[currKey].price
        ) {
            custom_rate = parseFloat(custom[currKey].price);
        }
        if (curr === 'AUD') {
            sign = 'AU$';
            let rt = custom_rate !== false ? custom_rate : parseFloat(rates['aud'] || 1);
            rate_value = round(rt, 4);
        } else if (curr === 'GBP') {
            sign = '£';
            let rt = custom_rate !== false ? custom_rate : parseFloat(rates['gbp'] || 1);
            rate_value = round(rt, 4);
        } else if (curr === 'EUR') {
            sign = '€';
            let rt = custom_rate !== false ? custom_rate : parseFloat(rates['eur'] || 1);
            rate_value = round(rt, 4);
        } else {
            sign = '$';
            rate_value = 1;
        }
        value = parseFloat(value) || 0;
        let final = rate_value * value;
        let formatted = numberFormat(final, decimals);
        // return symbol ? sign + formatted : formatted;

        if($('.perPerson').length > 0){
            return symbol ? sign + formatted + ' per person' : formatted + ' per person';
        }else{
            return symbol ? sign + formatted + 'pp' : formatted + 'pp';  
        }

    }
    function round(num, precision) {
        let factor = Math.pow(10, precision);
        return Math.round(num * factor) / factor;
    }
    function numberFormat(num, decimals) {
        return new Intl.NumberFormat('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(num);
    }
    async function getCurrency() {
        const params = new URLSearchParams(window.location.search);
        // GET param
        if (params.get('currency')) {
            let curr = params.get('currency').toUpperCase();
            document.cookie = "cc_format=" + curr + "; path=/; max-age=" + (30 * 24 * 60 * 60);
            return curr;
        }
        // COOKIE
        let cookie = getCookie('cc_format');
        if (cookie) return cookie.toUpperCase();

        let geo = await cfGeoLocation();
        console.log('geo:', geo);
        // ✅ SAVE COOKIE (IMPORTANT)
        document.cookie = "cc_format=" + geo + "; path=/; max-age=" + (30 * 24 * 60 * 60);
        if (geo) return geo;

        // GEO
        // if (ccdata.geo) return ccdata.geo;
        return ccdata.base || 'USD';
    }
    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length);
            }
        }
        return null;
    }
    function convertPrices() {
        let curr = window.CURRENCY;
        console.log(curr)
        $('.dyn-price').each(function () {
            let basePrice = parseFloat($(this).data('price'));
            if (isNaN(basePrice)) return;
            let formatted = currencyFormat(basePrice, true, 0, curr);
            $(this).html(formatted);
            // console.log('Converted price:', basePrice, 'to', formatted);
        });

        // ===== NEW: attribute conversion =====
        $('[price]').each(function () {

            let el = $(this);

            // ✅ ALWAYS use base price (source of truth)
            let basePrice = parseFloat(el.attr('data-base-price'));

            if (isNaN(basePrice)) return;

            // ✅ convert from base → current currency
            let converted = currencyFormat(basePrice, false, 0, window.CURRENCY);

            // ❗ ensure numeric (remove commas)
            converted = converted.toString().replace(/,/g, '');

            // ✅ update attribute
            el.attr('price', converted);

            // optional debug
            console.log({
                base: basePrice,
                converted: converted,
                currency: window.CURRENCY
            });
        });
    }
    function updateCurrencyUI(curr) {

        let symbol = getSymbol(curr);

        // ===== HEADER TEXT (MAIN FIX) =====
        if (curr === 'AUD'){
            $('.cc-current').html( '$' + ' ' + curr );
        }else{
            $('.cc-current').html(symbol + ' ' + curr );
        }

        // ===== MOBILE TEXT (KEEP IF USED) =====
        if (curr === 'AUD'){
            $('.cc-current-mobile').html( '$' + ' ' + curr );
        }else{
            $('.cc-current-mobile').html(symbol + ' ' + curr );
        }

        // ===== ACTIVE ITEM =====
        $('.auto_switcher_link').removeClass('current');
        let activeLinks = $('.auto_switcher_link[data-value="' + curr + '"]');
        activeLinks.addClass('current');

        // ===== PHONE SWITCH (KEEP EXISTING) =====
        let phoneData = $('.numbers-switcher li[data-curr="' + curr + '"]');

        if (phoneData.length) {
            let phone = phoneData.attr('data-phone');
            let link = phoneData.attr('data-link');

            if (phone && link && $('.cc-phone').length) {
                $('.cc-phone')
                    .text(phone)
                    .attr('href', 'tel:' + link);
            }
        }
    }
    function getSymbol(curr) {
        if (curr === 'AUD') return 'AU$';
        if (curr === 'GBP') return '£';
        if (curr === 'EUR') return '€';
        return '$';
    }
    $('.auto_switcher_link').on('click', function (e) {
        e.preventDefault();

        let curr = $(this).data('value');

        // ✅ update global currency (THIS IS THE FIX)
        window.CURRENCY = curr;

        // save cookie
        document.cookie = "cc_format=" + curr + "; path=/; max-age=" + (30 * 24 * 60 * 60);

        // update UI instantly
        updateCurrencyUI(curr);

        // update prices instantly
        convertPrices();

        if (window.innerWidth <= 991) {
            const menuBtn = $('.new_header .menu-button');

            if (menuBtn.hasClass('close')) {
                menuBtn.trigger('click');
            }

            $('.current-status').removeClass('active');
        }
    });

    (async function () {
        window.CURRENCY = await getCurrency();
        console.log('Final currency:', window.CURRENCY);

        updateCurrencyUI(window.CURRENCY);
        convertPrices();
    })();

    // let curr = getCurrency();
    // // console.log('Initial currency:', curr);
    // updateCurrencyUI(curr);
    // // console.log('UI updated for initial currency');
    // convertPrices();
});
