//$.noConflict();
// ================================ Cookies Script ===================== //
function setCookie(e, t, n) {
    var o = new Date;
    o.setTime(o.getTime() + 24 * n * 60 * 60 * 1e3);
    var i = "expires=" + o.toUTCString();
    document.cookie = e + "=" + t + ";" + i + ";path=/"
}

function getCookie(e) {
    for (var t = e + "=", n = decodeURIComponent(document.cookie).split(";"), o = 0; o < n.length; o++) {
        for (var i = n[o];
            " " == i.charAt(0);) i = i.substring(1);
        if (0 == i.indexOf(t)) return i.substring(t.length, i.length)
    }
    return ""
}
// ================================ Cookies Script ===================== //

jQuery(function ($) {
    // Get Theme Path From Function
    let $themeUrl = kingdomVision.themeUrl;
    let $baseCurrency = kingdomVision.baseCurrency;

    // ================================ Footer Menu Active Script ==========================================
    let currentUrl = window.location.href;
    $('.col ul li a').each(function() {
        if (this.href === currentUrl) {
            $(this).addClass('active'); 
            $(this).closest('li').addClass('active'); // Optional: adds class to parent <li>
        }
    });
    // ================================ Footer Menu Active Script ==========================================

    const videos = kingdomVision.videos;
    const images = kingdomVision.images;

    // ================================ CLOUD VIDEO HANDLER ==========================================
    if (videos.length > 0) {
        $(window).on('load resize', function() {
            if ($(window).width() < 767) {
                $('.mediaWrapper.cloudVideo').each(function(i) {
                    let video = videos[i];
                    if (!video) return;

                    let $desktop = $(this).find('.cloudVideoDesktop');
                    let $mobile = $(this).find('.cloudVideoMobile');

                    if (video.mobile) {
                        $desktop.hide();
                        $mobile.show();
                        $mobile.empty().append(video.mobile);
                    } else {
                        $mobile.hide();
                        $desktop.show();
                        $desktop.empty().append(video.desktop);
                    }
                });
            } else {
                $('.mediaWrapper.cloudVideo').each(function(i) {
                    let video = videos[i];
                    if (!video) return;

                    let $desktop = $(this).find('.cloudVideoDesktop');
                    let $mobile = $(this).find('.cloudVideoMobile');

                    $mobile.hide();
                    $desktop.show();
                    $desktop.empty().append(video.desktop);
                });
            }
        });
    }
    // ================================ CLOUD VIDEO HANDLER ==========================================
    // ================================ IMAGE HANDLER ==========================================
    if (images.length > 0) {
        $('.imageWrapper').each(function(i, el) {
            let image = images[i];
            if (!image) return;

            console.log(image.haveLightBox);

            if ($(window).width() < 767) {
                if (image.mobile) {
                    if (image.haveLightBox) {
                        let getImgUrl = $(image.mobile).attr('src');
                        $(el).find('a[data-fancybox="gallery"]').attr('href', getImgUrl);
                    }
                    $(el).append(image.mobile);
                } else {
                    if (image.haveLightBox) {
                        let getImgUrl = $(image.desktop).attr('src');
                        $(el).find('a[data-fancybox="gallery"]').attr('href', getImgUrl);
                    }
                    $(el).append(image.desktop);
                }
            } else {
                if (image.haveLightBox) {
                    let getImgUrl = $(image.desktop).attr('src');
                    $(el).find('a[data-fancybox="gallery"]').attr('href', getImgUrl);
                }
                $(el).append(image.desktop);
            }
        });
    }
    // ================================ IMAGE HANDLER ==========================================

    // Search Page
    jQuery('body.search').find('header').removeClass('transparent');

    // ================================ Add Class on scroll Script ===================== //
    $(window).scroll(function () {
        if ($(document).scrollTop() > 50) {
            $('.mainHeader').addClass('stickyHeader');
            $('.mobHeader').addClass('stickyHeader');
        } else {
            $('.mainHeader').removeClass('stickyHeader');
            $('.mobHeader').removeClass('stickyHeader');
        }
    });
    // ================================ Add Class on scroll Script ===================== //

    // ================================ Desktop Menu Script ===================== //
    $('.desktopMenu a.clickMenu').on('click', function (e) {
        e.preventDefault();

        let $this = jQuery(this);
        let $menu = jQuery('.menuWrapper');
        let $icon = $this.find('i');
        let $body = jQuery('body');

        if ($menu.is(':visible')) {
            // Menu is open — close it
            $menu.slideUp(300);
            $icon.removeClass('fa-xmark').addClass('fa-bars');
            $this.removeClass('active');
            $body.removeClass('menuActive');
        } else {
            // Menu is closed — open it
            $menu.slideDown(300);
            $icon.removeClass('fa-bars').addClass('fa-xmark');
            $this.addClass('active');
            $body.addClass('menuActive');
        }
    });
    // ================================ Desktop Menu Script ===================== //

    // ================================ Currency Switcher Script ===================== //
    // $('.auto_switcher_link').on('click', function (e) {
    //     e.preventDefault();
    //     setCookie('cc_format', $(this).data('value'));
    //     document.location.reload();
    // });
    // ================================ Currency Switcher Script ===================== //


    // ================================ Wysiwyg Read More Read Less Script ===================== //
    $(".contentWrapper").each(function () {
        let $section = $(this);
        let $readMore = $section.find(".wysiwygReadMore");
        let $readLess = $section.find(".wysiwygReadLess");
        let $fullContent = $section.find(".wysiwygFullContent");

        // Initially hide the "Read Less" button
        $readLess.hide();

        // READ MORE
        $readMore.on("click", function (e) {
            e.preventDefault();
            $fullContent.stop(true, true).slideDown(300); // show hidden content
            $readMore.hide();
            $readLess.show();
        });

        // READ LESS
        $readLess.on("click", function (e) {
            e.preventDefault();
            $fullContent.stop(true, true).slideUp(300); // hide again
            $readMore.show();
            $readLess.hide();
        });
    });
    // ================================ Wysiwyg Read More Read Less Script ===================== //

    // ================================ Slick Slider helper Script ===================== //
    function initSlider(selector, baseOptions) {
        let $sliders = $(selector);

        // if slider not found
        if ($sliders.length === 0) {
            // console.warn('No elements found for selector:', selector);
            return;
        }

        $sliders.each(function (index) {
            let $slider = $(this);

            // if already initialized 
            if ($slider.hasClass('slick-initialized')) {
                return;
            }

            let options = $.extend({}, baseOptions);
            // console.log(options);
            if (!options.prevArrow || !options.nextArrow) {
                // Find closest wrapper with section class
                let $sectionWrapper = $slider.closest('.repeaterArrows, .accordionImagesArrows, [class*="section-"]');
                let sectionClass = '';

                if ($sectionWrapper.length) {
                    let match = $sectionWrapper.attr('class').match(/section-\d+/);
                    // let match = $sectionWrapper.attr('class').match(/(section-\d+|arrowItems-\d+)/);
                    if (match) {
                        sectionClass = match[0];
                    }
                }

                // Section-specific arrows
                let $arrowsContainer = $sectionWrapper.find('.SliderArrows');
                if ($arrowsContainer.length) {
                    options.prevArrow = $arrowsContainer.find('.angel-arrow-left');
                    options.nextArrow = $arrowsContainer.find('.angel-arrow-right');
                }
            }

            // Initialize Slick safely
            try {
                $slider.slick(options);
            } catch (err) {
                console.error('Error initializing Slick on', selector, err);
            }
        });
    }
    // ================================ Slick Slider helper Script ===================== //

    // ================================ Arrow HTML generator Script ===================== //
    function arrow(className) {
        return $('<div>').addClass(className).append(
            $('<span>').append(
                $('<img>').attr('src', $themeUrl + '/images/carousel-arrow.svg').attr('alt', 'Arrow')
            )
        )[0].outerHTML;
    }

    function longArrow(className) {
        return $('<div class="longArrow">').addClass(className).append(
            $('<span>').append(
                $('<img>').attr('src', $themeUrl + '/images/carousel-arrow-long.svg').attr('alt', 'Arrow')
            )
        )[0].outerHTML;
    }
    // ================================ Arrow HTML generator Script ===================== //

    // ================================ Top Banner Script ===================== //

    /***********************************************
    * CLOUDLARE STREAM + SLICK VIDEO CONTROLLER
    ***********************************************/
    let cfPlayers = [];

    function scanCloudflarePlayers() {

        // console.log("🔥 Running CF scanner manually...");

        cfPlayers = [];

        $('.bannerSliderWrapper .slick-slide').each(function (i) {

            // Cloudflare videos only in correct wrapper
            let iframe = $(this).find(".mediaWrapper.cloudVideo iframe");

            // console.log("Slide", i, "CF iframe count:", iframe.length);

            if (iframe.length) {
                try {
                    let player = Stream(iframe[0]);

                    // 🔥 Force hide controls always
                    player.controls = false;

                    cfPlayers[i] = player;
                    console.log("🎉 CF Player initialized:", cfPlayers[i]);
                } catch (e) {
                    console.error("CF Player init error:", e);
                }
            }
        });

        // console.log("FINAL cfPlayers:", cfPlayers);
    }


    function setDurationTiming(player) {
        let waitForDuration = setInterval(() => {
            if (player.duration && player.duration > 0) {

                let durationMs = player.duration * 1000;

                // console.log("⏳ CF duration detected:", durationMs);

                jQuery('.bannerSliderWrapper').slick(
                    'slickSetOption', 'autoplaySpeed', durationMs, false
                );

                clearInterval(waitForDuration);
            }
        }, 200);

        // stop checking after 3 seconds
        setTimeout(() => clearInterval(waitForDuration), 3000);
    }

    function observeForCloudflareIframe() {
        const target = document.querySelector('.bannerSliderWrapper');

        if (!target) return;

        const observer = new MutationObserver(() => {
            let iframe = document.querySelector(".bannerSliderWrapper iframe[src*='cloudflarestream.com']");
            if (iframe) {
                console.log("🎉 Cloudflare iframe detected dynamically — scanning...");
                scanCloudflarePlayers();
                observer.disconnect(); // Stop watching once found
            }
        });

        observer.observe(target, {
            childList: true,
            subtree: true
        });
    }

    // run observer

    $('.bannerSliderWrapper').on('init', function () {
        // console.log("✨ Slick initialized — scanning Cloudflare players...");
        scanCloudflarePlayers();
    });

    // 
    document.addEventListener("touchstart", function iosUnlock() {
        const v = document.createElement("video");
        v.muted = true;
        v.playsInline = true;
        v.src = "";
        v.play().catch(() => {});
        document.removeEventListener("touchstart", iosUnlock);
    }, { once: true });


    // UTC Banner Slider
    const sliderEl = document.querySelector('.bannerSliderWrapper.activeSlider');
    const autoplaySpeed = sliderEl ?  parseInt(sliderEl.dataset.speed): 3000;
    if (sliderEl) {
        const autoplay = sliderEl.dataset.autoplay === 'true';
        const isHotelSlider = sliderEl.classList.contains('hotelSlider');
        // let hotelItem = [];
        // if (isHotelSlider) {
        //   hotelItem = sliderEl.querySelectorAll('.itemWrapper');
        //   console.log(hotelItem.length);
        // }
        const sliderOptions = { 
            dots: false,
            slidesToShow: isHotelSlider ? 3 : 1,
            // slidesToShow: isHotelSlider ? Math.min(hotelItem.length, 3) : 1,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: isHotelSlider ? '<div class="slick-arrow angel-arrow-left"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none"><path d="M1.19002 4.3438C1.86611 4.14308 2.63653 3.72105 3.25497 3.4174C3.68997 3.20124 4.09877 2.95419 4.48136 2.67113C5.4457 1.96089 6.23708 1.04478 6.78739 0H8.3754C8.3754 0 8.3754 0.0102934 8.3754 0.01544C7.70456 1.56459 6.59871 2.90273 5.20985 3.88574C4.89015 4.1122 4.57045 4.34894 4.21931 4.51879H15V5.48121H4.1669C4.52853 5.67164 4.87443 5.88266 5.20461 6.11426C6.59347 7.09727 7.69932 8.43541 8.37016 9.98456C8.37016 9.98456 8.37016 9.99485 8.37016 10H6.78215C6.23184 8.95008 5.43521 8.03911 4.47612 7.32887C4.09352 7.04581 3.67949 6.79876 3.24973 6.5826C2.63653 6.27895 2.04954 5.89295 1.36822 5.69223C0.131346 5.29079 0.000320435 5.02316 0.000320435 5.00772C0.000320435 4.99228 -0.0520887 4.76068 1.18478 4.35924L1.19002 4.3438Z" fill="#FCF4E7"></path></svg></div>' : arrow('angel-arrow-left'),
            nextArrow: isHotelSlider ? '<div class="slick-arrow angel-arrow-right"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none"><path d="M13.81 4.3438C13.1339 4.14308 12.3635 3.72105 11.745 3.4174C11.31 3.20124 10.9012 2.95419 10.5186 2.67113C9.5543 1.96089 8.76292 1.04478 8.21261 0H6.6246C6.6246 0 6.6246 0.0102934 6.6246 0.01544C7.29544 1.56459 8.40129 2.90273 9.79015 3.88574C10.1098 4.1122 10.4295 4.34894 10.7807 4.51879H0V5.48121H10.8331C10.4715 5.67164 10.1256 5.88266 9.79539 6.11426C8.40653 7.09727 7.30068 8.43541 6.62984 9.98456C6.62984 9.98456 6.62984 9.99485 6.62984 10H8.21785C8.76816 8.95008 9.56479 8.03911 10.5239 7.32887C10.9065 7.04581 11.3205 6.79876 11.7503 6.5826C12.3635 6.27895 12.9505 5.89295 13.6318 5.69223C14.8687 5.29079 14.9997 5.02316 14.9997 5.00772C14.9997 4.99228 15.0521 4.76068 13.8152 4.35924L13.81 4.3438Z" fill="#FCF4E7"></path></svg></div>' : arrow('angel-arrow-right'),
            fade: !isHotelSlider,
            speed: 800,
            infinite: isHotelSlider ? false : true,
            cssEase: 'cubic-bezier(0.77, 0, 0.175, 1)',
            autoplay: false,
            autoplaySpeed: 3000,
            adaptiveHeight: true,
            pauseOnHover: false,
            responsive: [
              {
                breakpoint: 1024, 
                settings: {
                  slidesToShow: isHotelSlider ? 2 : 1,
                  slidesToScroll: isHotelSlider ? 2 : 1,
                }
              },
              {
                breakpoint: 767, 
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1,
                }
              }
            ]
        };

        if (autoplay && !isNaN(autoplaySpeed)) {
            sliderOptions.autoplay = true;
            sliderOptions.autoplaySpeed = autoplaySpeed;
        }

        initSlider('.bannerSliderWrapper.activeSlider', sliderOptions);

        observeForCloudflareIframe();
    }

    // Store iframe src for later use
    $(".bannerSliderWrapper .mediaWrapper iframe").each(function () {
        $(this).attr("data-src", $(this).attr("src"));
    });

    // BEFORE slide change → PAUSE all videos
    $('.bannerSliderWrapper').on('beforeChange', function (event, slick, current, next) {

        // console.log("⏸ BEFORE: Pausing all videos + resetting autoplaySpeed");

        // Reset slick autoplaySpeed to default EVERY time BEFORE slide changes
        jQuery('.bannerSliderWrapper').slick(
            'slickSetOption',
            'autoplaySpeed',
            autoplaySpeed,
            false
        );

        slick.$slides.each(function () {

            let slide = $(this);

            // ----------- YOUTUBE PAUSE -----------
            let yt = slide.find(".mediaWrapper.youtubeVideo iframe");
            if (yt.length) {
                try {
                    yt[0].contentWindow.postMessage(
                        '{"event":"command","func":"pauseVideo","args":""}', '*'
                    );
                } catch (e) { }
            }

            // // ----------- CLOUDFLARE STREAM PAUSE -----------
            // let cloud = slide.find(".mediaWrapper.cloudVideo iframe");
            // if (cloud.length) {
            //     try {
            //         cloud[0].contentWindow.postMessage(
            //             '{"event":"cloudflare-stream-player","method":"pause"}',
            //             '*'
            //         );
            //     } catch (e) { }
            // }

            // ----------- WORDPRESS VIDEO PAUSE -----------
            let wp = slide.find(".mediaWrapper.wordpressVideo video");
            if (wp.length) {
                wp.get(0).pause();
            }

        });
    });


    // AFTER slide change → PLAY only current slide
    $('.bannerSliderWrapper').on('afterChange', function (event, slick, current) {

        let slide = $(slick.$slides[current]);

        // ----------- YOUTUBE PLAY (muted + loop) -----------
        let yt = slide.find(".mediaWrapper.youtubeVideo iframe");
        if (yt.length) {
            let src = yt.attr("data-src");

            // YT must have loop + mute + autoplay + enablejsapi
            let finalSrc =
                src +
                (src.includes("?") ? "&" : "?") +
                "autoplay=1&mute=1&loop=1&playlist=" +
                extractYouTubeID(src) +
                "&enablejsapi=1";

            yt.attr("src", finalSrc);
        }

        /* CLOUDFLARE AUTOPLAY */
        let iframe = slide.find("iframe[src*='cloudflarestream.com']").not(".slick-cloned")[0];

        if (iframe) {
            let player = cfPlayers[current];

            if (player) {
                console.log("▶ CF: Starting video");

                if (player.currentTime) player.currentTime = 0;

                player.controls = false; // 🔥 hide again just in case
                player.play();

                // Always control timing
                setDurationTiming(player);

                player.addEventListener("ended", () => {
                    jQuery('.bannerSliderWrapper').slick(
                        'slickSetOption', 'autoplaySpeed', autoplaySpeed, true
                    );
                });
            }
        }

        // ----------- WORDPRESS VIDEO AUTOPLAY + LOOP + MUTE -----------
        let wp = slide.find(".mediaWrapper.wordpressVideo video");
        if (wp.length) {
            wp.get(0).muted = true;
            wp.get(0).loop = true;
            wp.get(0).play();
        }
    });


    // Helper → Extract YouTube video ID for loop playlist
    function extractYouTubeID(url) {
        let match = url.match(/embed\/([^?]+)/);
        return match ? match[1] : "";
    }

    // ================================ Top Banner Script ===================== //

    // ================================ Top Banner Parallax Script ===================== //
    $(window).on("scroll", function () {
        $(".itemMediaWrapper.activeParallax .imageWrapper").each(function () {
            let speed = 0.4; // ← parallax speed
            let offset = $(window).scrollTop() * speed;
            if($('body').hasClass('single-hotel_information')){
                if(offset <= 80){
                    $(this).css("transform", "inherit");
                }else{
                    $(this).css("transform", "translateY(" + offset + "px)");
                }
            }else{
                $(this).css("transform", "translateY(" + offset + "px)");
            }
            
        });
    });
    // ================================ Top Banner Parallax Script  ===================== //

    // ================================ promoSlider Script ===================== //
    initSlider('.promoSlider.activeSlider', {
        dots: true,
        slidesToShow: 1,
        arrows: true,
        speed: 900,
        infinite: true,
    });
    // ================================ promoSlider Script ===================== //

    // ================================ Review Slider Script ===================== //
    initSlider('.reviewsCarouselWrapper', {
        dots: false,
        slidesToShow: 1,
        arrows: true,
        prevArrow: arrow('angel-arrow-left'),
        nextArrow: arrow('angel-arrow-right'),
        speed: 900,
        infinite: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    dots: true,
                    arrows: true,
                    adaptiveHeight: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]

    });
    // ================================ Review Slider Script ===================== //

    // ================================ Publication Slider Script ===================== //
    $('.publicationRepeater.activeSlider').each(function () {
        const $this = $(this);
        const $items = parseInt($this.attr('slideritem'));
        console.log($items);
        initSlider($this, {
            dots: true,
            slidesToShow: $items,
            slidesToScroll: $items,
            arrows: true,
            speed: 900,
            infinite: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: $items,
                        slidesToScroll: $items
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: $items,
                        slidesToScroll: $items
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        dots: true,
                        slidesToShow: $items,
                        slidesToScroll: $items
                    }
                },
                
            ]
        });
    });
    // ================================ Publication Slider Script ===================== //
    
    // ================================ Custom Reviews Script ===================== //
    initSlider('.custReviews', {
        dots: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        speed: 900,
        infinite: true,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    // ================================ Custom Reviews Script ===================== //

    // ================================ TravelWrapper Slide In & Out Container Script ===================== //
    $('.travelWrapper.activeSlider').each(function () {
        const $this = $(this);
        initSlider($this, {
            arrows: true,
            dots: true,
            slidesToShow: $this.hasClass('ofc') ? 5 : 4,
            slidesToScroll: 1,
            speed: 900,
            infinite: true,
            centerMode: false,
            responsive: [
                {
                    breakpoint: 1680,
                    settings: {
                        slidesToShow: $this.hasClass('ofc') ? 4 : 4,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: $this.hasClass('ofc') ? 3 : 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: $this.hasClass('ofc') ? 2 : 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });

    $('.travelWrapper.activeSlider .slick-dots').each(function () {
        let $wrapper = $('<div class="SliderdotsCover"></div>');
        $(this).wrap($wrapper);
    });
    // ================================ TravelWrapper Slide In & Out Container Script ===================== //

    // ================================ blogWrapper Slide In & Out Container Script ===================== //
    $('.blogWrapper.activeSlider').each(function () {
        const $this = $(this);
        const items = $this.attr('carouselitem') || 4;

        initSlider($this, {
            slidesToShow: $this.hasClass('ofc') ? items : 3,
            slidesToScroll: 1,
            speed: 900,
            infinite: true,
            centerMode: true,
            adaptiveHeight: true,
            arrows: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 1680,
                    settings: {
                        slidesToShow: $this.hasClass('ofc') ? items : 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: $this.hasClass('ofc') ? 3 : 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: $this.hasClass('ofc') ? 2 : 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
    // ================================ blogWrapper Slide In & Out Container Script ===================== //

    // ================================ contentCarousel Slide Out In Container Script ===================== //
    initSlider('.contentCarousel.activeSlider', {
        dots: false,
        slidesToShow: 1,
        speed: 900,
        infinite: true,
        centerMode: true,
        arrows: true,
        prevArrow: arrow('angel-arrow-left'),
        nextArrow: arrow('angel-arrow-right'),
    });
    // ================================ contentCarousel Slide Out In Container Script ===================== //

    // ================================ Custom Comments Script ===================== //
    $('.multipleComments.activeSlider').each(function () {
        const $this = $(this);
        initSlider($this, {
            dots: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            speed: 900,
            infinite: true,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
    // ================================ Custom Comments Script ===================== //

    // ================================ hotelSliderWrapper Script ===================== //
    $('.hotelWrapper.activeSlider').each(function () {
        const $this = $(this);

        initSlider($this, {
            dots: true,
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: true,
            speed: 900,
            infinite: true,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
    // ================================ hotelSliderWrapper Script ===================== //

    // ================================ listingWrapper Script ===================== //
    initSlider('.listingWrapper.activeSlider', {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
        speed: 900,
        infinite: true,
    });
    // ================================ listingWrapper Script ===================== //

    // ================================ itinerariesListing Script ===================== //
    initSlider('.itinerariesListing.activeSlider', {
        arrows: true,
        dots: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        speed: 900,
        infinite: true,
        adaptiveHeight: false,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
        ]
    });
    // ================================ itinerariesListing Script ===================== //

    // ================================ Ship Gallery Script ===================== //
    initSlider('.galleriesWrapper.activeSlider', {
        arrows: true,
        dots: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        speed: 900,
        infinite: true,
        adaptiveHeight: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    // ================================ Ship Gallery Script ===================== //

    // ================================ Ship 50/50 Block Script ===================== //
    initSlider('.fiftyImagesWrapper.activeSlider', {
        dots: false,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 900,
        infinite: true,
        adaptiveHeight: true,
    });
    // ================================ Ship 50/50 Block Script ===================== //
    
    // ================================ tabContSlider Product Script ===================== //
    $('.tabContSlider.activeSlider').each(function () {
        const $this = $(this);
        initSlider($this, {
            dots: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            speed: 900,
            infinite: true,
            adaptiveHeight: true,
            lazyLoad: 'ondemand',
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        dots: true,
                    }
                },
            ]
        });
    });
    // ================================ tabContSlider Product Script ===================== //

    // ================================  Product Ship Tabs Script ===================== //
    function proShipTabs(){
        // Show the first tab and hide the rest
        $('.shipTabWrapper .shipTabs .shipTabsItem:first-child').addClass('active');
        $('.shipTabsContent .TabsContItem').hide();
        $('.shipTabsContent .TabsContItem:first').show();

        // Click function
        $('.shipTabWrapper .shipTabs .shipTabsItem').click(function(e){
            e.preventDefault();
            $('.shipTabWrapper .shipTabs .shipTabsItem').removeClass('active');
            $(this).addClass('active');
            $('.shipTabsContent .TabsContItem').hide();
        
            let activeTab = $(this).find('a').attr('href');
            $(activeTab).fadeIn();

            if($('.tabContSlider.activeSlider').hasClass('slick-initialized')){
                $('.tabContSlider.activeSlider').slick('refresh');
            }

            return false;
        });
    }
    proShipTabs();
    // ================================  Product Ship Tabs Script ===================== //

    // ================================  Product Departure tabs Script ===================== //
    function proDepartureTabs(){

        $('.departureWrapper').each(function(){

            let $wrapper = $(this);
            // First tab active
            $wrapper.find('.yearsTabs .yearsItem:first-child').addClass('active');

            // Hide all tab content of current wrapper
            $wrapper.find('.yearsTabContent .yearsTabItem').hide();
            $wrapper.find('.yearsTabContent .yearsTabItem:first').show();

            // Click event
            $wrapper.find('.yearsTabs .yearsItem').on('click', function(e) {
                e.preventDefault();

                $wrapper.find('.yearsTabs .yearsItem').removeClass('active');
                $(this).addClass('active');

                $wrapper.find('.yearsTabContent .yearsTabItem').hide();

                let activeTab = $(this).find('a').attr('href');
                $wrapper.find(activeTab).fadeIn();
                
                if($wrapper.find('.eventWrap.activeSlider').hasClass('slick-initialized')){
                    $wrapper.find('.eventWrap.activeSlider').slick('refresh');
                }

                if($(window).width() < 991){
                    if($wrapper.find('.eventWrap').hasClass('slick-initialized')){
                        $wrapper.find('.eventWrap').slick('refresh');
                    }
                }

            });

        })

        // // Show the first tab and hide the rest
        // $('.departureWrapper .yearsTabs .yearsItem:first-child').addClass('active');
        // $('.yearsTabContent .yearsTabItem').hide();
        // $('.yearsTabContent .yearsTabItem:first').show();

        // // Click function
        // $('.departureWrapper .yearsTabs .yearsItem').click(function(){
        //     $('.departureWrapper .yearsTabs .yearsItem').removeClass('active');
        //     $(this).addClass('active');
        //     $('.yearsTabContent .yearsTabItem').hide();
        
        //     let activeTab = $(this).find('a').attr('href');
        //     $(activeTab).fadeIn();
        //     return false;
        // });
    }
    proDepartureTabs();

    // Product Departure tabs
    function proDepartureInnerTabs(){

        // Show the first tab and hide the rest
        $('.monthsTabs .monthsItem:first').addClass('active');
        $('.monthsTabContent .monthsTabItem').hide();
        $('.monthsTabContent .monthsTabItem:first').show();

        // Click function
        $('.monthsTabs .monthsItem').click(function(){
            $('.monthsTabs .monthsItem').removeClass('active');
            $(this).addClass('active');
            $('.monthsTabContent .monthsTabItem').hide();
        
            // let activeTab = $(this).find('a').attr('href');
            let activeTab = $(this).find('a').data('months');
            // $(activeTab).fadeIn();
            $('.monthsTabItem[data-month-item="' + activeTab + '"]').fadeIn();
            $('.monthsTabContent .monthsTabItem[id="' + activeTab + '"]').fadeIn();
            
            if($('.dateEventWrapper.activeSlider').hasClass('slick-initialized')){
                $('.dateEventWrapper.activeSlider').slick('refresh');
            }

            if($(window).width() < 991){
                if($('.dateEventWrapper').hasClass('slick-initialized')){
                    $('.dateEventWrapper').slick('refresh');
                }

                if($('.eventWrap').hasClass('slick-initialized')){
                    $('.eventWrap').slick('refresh');
                }
            }
            return false;
        });
    }
    proDepartureInnerTabs();

    $(".departureWrapper .yearsTabContent .yearsTabItem .mdWrapper").each(function (i) {
        let $wrapper = $(this);

        $wrapper.addClass("active");
        if (i === 0) {
            $wrapper.find(".departureAcc").show();
            $wrapper.find(".monthsNameWrap").addClass("active");
        } else {
            $wrapper.find(".departureAcc").hide();
        }

        $wrapper.find(".monthsNameWrap").click(function () {
            let $this = $(this);
            $this.toggleClass("active");
            $this.next(".departureAcc").slideToggle();

            if($wrapper.find('.eventWrap.activeSlider').hasClass('slick-initialized')){
                $wrapper.find('.eventWrap.activeSlider').slick('refresh');
            }
        });
    });

    // Product Departure tabs Slider
    $('.dateEventWrapper.activeSlider').each(function () {
        const $this = $(this);
        initSlider($this, {
            dots: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            speed: 900,
            infinite: true,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
            ]
        });
    });
    // ================================  Product Departure tabs Script ===================== //

    // ================================ Departure Date Updated Script ===================== //
    $('.utc-departure-dates-updated .eventWrap.activeSlider').each(function () {
        const $this = $(this);
        initSlider($this, {
            dots: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            speed: 900,
            infinite: true,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
            ]
        });
    });
    // ================================ Departure Date Updated Script ===================== //

    // ================================  ship-accordion Script ===================== //
    $('.accImagesWrapper.activeSlider').each(function () {
        const $this = $(this);
        initSlider($this, {
            dots: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            speed: 900,
            infinite: true,
        });
    });

    $(".shipsAccWrapper").each(function () {
        let $wrapper = $(this);
        // show first faqs all section
        $wrapper.find(".shipsAccItem:first .accTitle").addClass("active");
        $wrapper.find(".shipsAccItem:first .accContent").show();

        $wrapper.find(".shipsAccItem:gt(0) .accContent").hide();
        $wrapper.find(".accTitle").click(function () {
            let $this = $(this);
            $this.toggleClass("active");
            $this.next(".accContent").slideToggle();

            if($('.accImagesWrapper.activeSlider').hasClass('slick-initialized')){
                $('.accImagesWrapper.activeSlider').slick('refresh');
            }
        });
    });
    // ================================  ship-accordion Script ===================== //

    // ================================  journeyWrapper Script ===================== //
    initSlider('.journeyWrapper.activeSlider', {
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        speed: 900,
        infinite: true,
        variableWidth: true,
        cssEase: "linear",
    });
    // ================================  journeyWrapper Script ===================== //

    // ================================  Mobile Footer Script ===================== //
    $('.footer_links .mobTitle').on('click', function () {
        $(this).toggleClass('active');
        let $ul = $(this).closest('.footer_title, div').next('ul.links');
        // only first ul toggle
        $ul.slideToggle(300);
        // Optional: All Ul close
        $('.footer_links .links').not($ul).slideUp(300);
    });
    // ================================ Mobile Footer Script ===================== //

    // ================================ Gallery Script ===================== //
    initSlider('.gallery .galleryWrap.activeSlider', {
        dots: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        speed: 900,
        infinite: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    // ================================  Gallery Script ===================== //

    // ================================  Team Page Script ===================== //
    initSlider('.our-teams .teamWrapper.sliderActive', {
        slidesToShow: 3,
        slidesToScroll: 1,
        speed: 900,
        infinite: true,
        arrows: true,
        dots: true,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    // ================================  Team Page Script ===================== //

    // ================================   Desktop and Laptop Script ===================== //
    $(window).on('load resize', function () {
        if ($(window).width() > 992) {
            // TeamWrapper Home Page
            initSlider('.teamWrapper.activeSlider', {
                dots: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                arrows: true,
            });

            // ProcessSteps Home Page
            initSlider('.processSteps.activeSlider', {
                dots: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                arrows: true,
            });

        }
    });
    // ================================  Desktop and Laptop Script  ===================== //

    // ================================  Table / Mobile Slider Script (991) ===================== //
    $(window).on('load resize', function () {
        if ($(window).width() < 991) {
            $('.travelWrapper.horizontal').slick('unslick');
            initSlider('.travelWrapper', {
                arrows: true,
                dots: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                ]
            });

            initSlider('.processSteps , .teamWrapper', {
                arrows: true,
                dots: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                adaptiveHeight: true,
                responsive: [
                    // {
                    //     breakpoint: 991,
                    //     settings: {
                    //         slidesToShow: 3,
                    //         slidesToScroll: 1
                    //     }
                    // },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                ]
            });

            initSlider('.intaFeedRepeater', {
                dots: true,
                arrows: false,
                slidesToShow: 2,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                adaptiveHeight: true,
                responsive: [
                    // {
                    //     breakpoint: 991,
                    //     settings: {
                    //         slidesToShow: 2,
                    //         slidesToScroll: 1,
                    //     }
                    // },
                    {
                        breakpoint: 767,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            initSlider('.awardsRepeater.activeSlider', {
                dots: false,
                arrows: true,
                slidesToShow: 2,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                centerMode: true,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            initSlider('.blogWrapper', {
                arrows: true,
                dots: true,
                slidesToShow: 2,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                centerMode: true,
                adaptiveHeight: true,
                responsive: [
                    // {
                    //     breakpoint: 991,
                    //     settings: {
                    //         slidesToShow: 2,
                    //         slidesToScroll: 1
                    //     }
                    // },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            // itinerariesListing
            $('.itinerariesListing').each(function () {
                let $this = $(this);
                let $imgVideoItems = $this.find('.itinerariesItem ');
                if($imgVideoItems.length > 1) {
                    initSlider($this, {
                        arrows: true,
                        dots: true,
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        speed: 900,
                        infinite: true,
                        centerMode: true,
                        adaptiveHeight: false,
                        responsive: [
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                }
            });

            $('section').not('.separatorBlock').each(function () {
              this.style.removeProperty('padding-top');
              this.style.removeProperty('padding-bottom');
              this.style.removeProperty('margin-top');
              this.style.removeProperty('margin-bottom');
            });

            $('.multipleComments').each(function () {
                const $this = $(this);
                initSlider($this, {
                    dots: true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    arrows: true,
                    speed: 900,
                    infinite: true,
                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            });
         
            // Product Departure tabs Slider
            $('.dateEventWrapper').each(function () {
                const $this = $(this);
                initSlider($this, {
                    dots: true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    arrows: true,
                    speed: 900,
                    infinite: true,
                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            });

            initSlider('.gallery .galleryWrap', {
                dots: true,
                arrows: true,
                slidesToShow: 2,
                slidesToScroll: 1,
                speed: 900,
                infinite: true,
                responsive: [
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            // ================================  Depeature Dates Updated Script ===================== //
            $('.utc-departure-dates-updated .eventWrap').each(function () {
                const $this = $(this);
                let $eventItem = $this.find('.eventItem');
                if ($eventItem.length > 2) {
                    initSlider($this, {
                        dots: true,
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        arrows: true,
                        speed: 900,
                        infinite: true,
                        responsive: [
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                }
            });
            // ================================  Depeature Dates Updated Script ===================== //

            // ================================ Publication Slider Script ===================== //
            $('.publicationRepeater').each(function () {
                const $this = $(this);
                initSlider($this, {
                    dots: true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    arrows: true,
                    speed: 900,
                    infinite: true,
                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                dots: true,
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                        
                    ]
                });
            });
            // ================================ Publication Slider Script ===================== //

        }
        // ================================  Table / Mobile Slider Script (991) ===================== //

        // ================================  Mobile Slider Script (767) ===================== //
        if ($(window).width() < 767) {
            $('.imgVideoWrapper').each(function () {
                let $this = $(this);
                let $imgVideoItems = $this.find('.imgWrap');

                if ($imgVideoItems.length > 1) {
                    initSlider($this, {
                        dots: true,
                        arrows: false,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        speed: 900,
                        infinite: true,
                        centerMode: true,
                        adaptiveHeight: true,
                    });
                }
            });

            // 
            $('.itinerary-accordion .imagesWrapper').each(function () {
                let $this = $(this);
                let $imgVideoItems = $this.find('.col');

                if ($imgVideoItems.length > 1) {
                    initSlider($this, {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        speed: 900,
                        infinite: false,
                        centerMode: true,
                        adaptiveHeight: true,
                        dots: true
                    });
                }
            });

            // 
            $('.itinerary-accordion .hotelSliderWrapper .hotelWrapper ').each(function () {
                let $this = $(this);
                let $imgVideoItems = $this.find('.hotelBox');

                if ($imgVideoItems.length > 1) {
                    initSlider($this, {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        speed: 900,
                        infinite: true,
                        centerMode: true,
                        adaptiveHeight: true,
                        dots: true
                    });
                }
            });

            // ================================  Depeature Dates Updated Script ===================== //
            $('.utc-departure-dates-updated .eventWrap').each(function () {
                const $this = $(this);
                let $eventItem = $this.find('.eventItem');
                if ($eventItem.length > 1) {
                    initSlider($this, {
                        dots: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: true,
                        speed: 900,
                        infinite: true,
                    });
                }
            });
            // ================================  Depeature Dates Updated Script ===================== //

            // ================================ Publication Slider Script ===================== //
            $('.publicationRepeater').each(function () {
                const $this = $(this);
                initSlider($this, {
                    dots: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    speed: 900,
                    infinite: true,
                });
            });
            // ================================ Publication Slider Script ===================== //

        }

    });
    // ================================  Mobile Slider Script (767) ===================== //

    // ================================  Jumplinks Script  ===================== //

    // jumplinks Jquery
    // let $toggleJumplink = $('.jumplinks.toggle');
    // $toggleJumplink.find('.mobJumplink').addClass('active');

    // $(document).on('click' , '.mobJumplink', function(){
    //     $(this).toggleClass('active');
    //     $(this).parents(".jumplinks").find("ul").slideToggle();
    // });

    $(document).on('click' , '.jumplinks ul li a', function(e){
        const attrName = 'target';
        if(!$(this).attr(attrName)){
            e.preventDefault();
        }        
        let selectedText = $(this).text(); 
        $(this).parents('.jumplinks').find('.mobJumplink span').text(selectedText);

        // $('.mobJumplink').removeClass('active');
        // if ($(window).width() <= 767) {
        //     $(this).parents('.jumplinks').find('ul').slideUp();
        // }

        let currLink = $(this).attr('href');
        if ($(currLink).length) {
            let $target = currLink.replace(/#/g, '');
            let $section = $(`section[id="${$target}"]`).offset().top;
           
            if($section){
                if ($(window).width() >= 767) {
                    let $minusTop = '';
                    // if($toggleJumplink.length){
                    //     $minusTop = $('.mainHeader').innerHeight() ;
                    // }else{
                    //     $minusTop = $('.jumplinks').innerHeight() + $('.mainHeader').innerHeight();
                    // }
                    $minusTop = $('.jumplinks').innerHeight() + $('.mainHeader').innerHeight();
                    $('html, body').animate({
                        scrollTop: $(`section[id="${$target}"]`).offset().top - $minusTop
                    }, 0);
                }
                if ($(window).width() <= 767) {
                    let $minusTop = $('#jumplinks-sec').innerHeight() + $('.mobHeader').innerHeight();
                    console.log($minusTop);
                    $('html, body').animate({
                        scrollTop: $(`section[id="${$target}"]`).offset().top - $minusTop
                    }, 0);
                }
            }
        }

    });

    if ($(window).width() <= 767) {
        $('.jumplinks').removeClass('toggle');
    }

    // Active on srcoll
    $('.jumplinks ul li a').on('click', function (e) {
        $('.jumplinks ul li a').removeClass('active');
        $(this).addClass('active');
    });

    $(window).on('scroll', function () {
        let scrollPos = $(window).scrollTop();

        $('.jumplinks ul li a[href^="#"]').each(function () {
            let currLink = $(this);
            let ref = currLink.attr('href');

            if ($(ref).length) {
                let sectionTop = $(ref).offset().top - 200;
                let sectionBottom = sectionTop + $(ref).outerHeight();

                if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
                    $('.jumplinks ul li a').removeClass('active');
                    currLink.addClass('active');
                }
            }
        });
    });
    // ================================  Jumplinks Script  ===================== //

    // ================================  New Mobile Nav Script  ===================== //
    $(document).on('click', '.mobMenu a.clickMenu', function (e) {
        e.stopPropagation();
        $('.mobile-main-menu').toggleClass('active');
        $('body').toggleClass('open');
        $('.mobMenu').toggleClass('close');
    });

    //    CLick outside close
    $(document).on('click', 'body', function (e) {
        $('.mobile-main-menu').removeClass('active');
        $('body').removeClass('open');
        $('.mobile-main-menu .sub-menu').removeClass('active')
        $('.mobile-main-menu .sub-menu .submenu-close').remove();
        $('.mobMenu').removeClass('close');
        $('.mobile-main-menu .menu-item').removeClass('open');
        
    });

    $(document).on('click', '.navigation-wrapper', function (e) {
        e.stopPropagation();
    });

    //    Menu Bars Click Event Close
    $(document).on('click', '.mobMenu.close', function (e) {
        //   Toggle Sidebar menu;
        e.stopPropagation();
        $('.mobile-main-menu .sub-menu').removeClass('active')
        $('.mobile-main-menu .sub-menu .submenu-close').remove();
    });

    $('.mobile-main-menu li.menu-item-has-children').append('<div class="trig">X</div>');

    $(document).on('click', '.mobile-main-menu li.menu-item-has-children .trig', function (e) {
        e.stopPropagation();
        let submenu = $(this).prev();
        myabc = $(this);
        $(this).parent().addClass('open');
        if (!submenu.hasClass('active')) {
            submenu.prepend('<li class="submenu-close">Back to list</li>').addClass('active');
        }
    });

    jQuery(document).on('click', '.mobile-main-menu li.menu-item-has-children', function (e) {
        e.stopPropagation();
        if (jQuery(this).find('a:first').attr('href') != "#") return;
        // e.preventDefault();
        let subMenu = jQuery(this).find('.sub-menu:first');
        jQuery(this).addClass('open');
        if (!subMenu.hasClass('active')) {
            subMenu.prepend('<li class="submenu-close">Back to list</li>').addClass('active');
        }
    });

    $(document).on('click', '.childrenmenu', function (e) {
        e.stopPropagation();
        if ($(this).find('a:first').attr('href') != "#") return;
        let submenu = $(this).find('.sub-menu');
        if (!submenu.hasClass('active')) {
            submenu.prepend('<li class="submenu-close">Back to list</li>').addClass('active');
        }
    });

    $(document).on('click', '.mobile-main-menu li.submenu-close', function (e) {
        e.stopPropagation();
        $(this).parents('.menu-item').removeClass('open');
        $(this).parent().removeClass('active');
        $(this).remove();
    });

    // $('.navigation-wrapper ul.auto_switcher').hide();
    $('.navigation-wrapper .currency-mobile .current-status').on('click', function () {
        $('.navigation-wrapper ul.auto_switcher').toggleClass('active');
        $('.navigation-wrapper .currency-mobile .current-status').toggleClass('active');
    });

    $('.navigation-wrapper ul.auto_switcher li , .navigation-wrapper ul.auto_switcher li a').on('click', function (e) {
        e.stopPropagation();
        $(this).closest('ul.auto_switcher').removeClass('active');
        $('.mobile-main-menu').removeClass('active');
        $('body').removeClass('open');
        $('.mobMenu').removeClass('close');
    });


    // ================================  New Mobile Nav Script ===================== //

    // ================================ Faqs module script  ===================== //
    $(".faqListing").each(function () {
        let $defaultOpen = parseInt(
            $(this).attr("data-open"),
            10
        );

        // Sab hide
        $(this).find(".answer").hide();
        $(this).find(".question").removeClass("active");

        $(this).find(".faqItem").slice(0, $defaultOpen).each(function () {

            $(this).find(".answer").show();
            $(this).find(".question").addClass("active");

        });

        // $(".question").click(function () {
        //     let $this = $(this);
        //     let $section = $this.closest(".faqs");
        //     if ($this.hasClass("active")) {
        //         return;
        //     }
        //     $section.find(".answer").slideUp();
        //     $section.find(".question").removeClass("active");
        //     // Current open 
        //     $this.addClass("active");
        //     $this.next(".answer").slideDown();
        // });

        $(this).find(".question").click(function(){
            let $this = $(this);
            $this.toggleClass("active");
            $this.next(".answer").slideToggle();
        })
    });
    // ================================ Faqs module script  ===================== //

    // ================================ itinerary-accordion module script  ===================== //
    $(".itinerary-accordion").each(function () {
        // show first faqs all section
        $(this).find(".itineraryAccItem:first .accContent").show();
        $(this).find(".itineraryAccItem:first .accTitle").addClass("active");

        $(this).find(".itineraryAccItem:gt(0) .accContent").hide();
        $(".accTitle").click(function () {
            console.log(this);
            let $this = $(this);
            $this.toggleClass("active");
            $this.next(".accContent").slideToggle();

            if($('.imagesWrapper').hasClass('slick-initialized')){
                $('.imagesWrapper').slick('refresh');
            }

            if($('.hotelWrapper').hasClass('slick-initialized')){
                $('.hotelWrapper').slick('refresh');
            }

        });
    });
    // ================================ itinerary-accordion module script  ===================== //

    // ================================ Bullets Points Read More / Read Less  ===================== //
    function bulletsPointRlmore() {
        $('.columnsWrapper.onlyMobile').each(function () {
            const $wrapper = $(this);
            const $list = $wrapper.find('ul');
            const $items = $list.find('li');

            const limit = 10; // default 10 if not set

            // Only apply if there are more than limit items
            if ($items.length > limit) {
                // Hide items after limit
                $items.slice(limit).hide();

                // Create and insert Read More button
                const $toggleBtn = $('<div class="rlmore"><a href="#" class="read-toggle">Read More </a> <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M5.6562 6.86332C5.85692 6.21754 6.27895 5.48164 6.5826 4.89093C6.79876 4.47542 7.04581 4.08495 7.32887 3.71951C8.03912 2.79839 8.95522 2.04247 10 1.51684V0C10 0 9.98971 0 9.98456 0C8.43541 0.640776 7.09727 1.69706 6.11426 3.02366C5.8878 3.32903 5.65105 3.6344 5.48121 3.96981C5.48121 3.96981 5.18793 4.64707 5 4.65684C4.81207 4.66662 4.51879 4.01987 4.51879 4.01987C4.32836 3.67445 4.11734 3.34405 3.88574 3.02867C2.90273 1.70206 1.56459 0.645782 0.01544 0.00500555C0.01544 0.00500555 0.00514698 0.00500555 0 0.00500555V1.52184C1.04992 2.04748 1.96089 2.8084 2.67113 3.72451C2.95419 4.08996 3.20123 4.48544 3.4174 4.89593C3.72105 5.48164 4.10705 6.04232 4.30777 6.69311C4.70921 7.87454 4.97684 7.99969 4.99228 7.99969C5.00772 7.99969 5.23932 8.04976 5.64076 6.86832L5.6562 6.86332Z" fill="#F2E2C4"/></svg></div>');
                $list.after($toggleBtn);

                // Attach click handler
                $('.rlmore a.read-toggle').on('click', function (e) {
                    e.preventDefault(); // prevent page jump

                    const $hiddenItems = $items.slice(limit);

                    if ($hiddenItems.is(':visible')) {
                        $hiddenItems.slideUp();
						$(this).closest('.rlmore').removeClass('active');
                        $(this).text('Read More');
                    } else {
                        $hiddenItems.slideDown();
						$(this).closest('.rlmore').addClass('active');
                        $(this).text('Read Less');
                    }
                });
            }
        });
    }
    bulletsPointRlmore();
    // ================================Bullets Points Read More / Read Less  ===================== //
    
    // ================================ Search Icon  ===================== //
    $(document).on('click', '.seachIconWrapper', function (e) {
        e.preventDefault();
        $('.popSearchBox').addClass('active');
    })

    $(document).on('click', '.popSearchBox .close', function (e) {
        e.preventDefault();
        $('.popSearchBox').removeClass('active');
    });
    // ================================ Search Icon  ===================== //

    // ================================ Single Blog Story  ===================== //
    $(document).on('click' , '.storyToggle', function(e){
        e.preventDefault();
        $(this).closest('.storyWrapper').find('.storyShare').fadeIn().addClass('active');
    });

    $(document).on('click' , '.closeStory', function(e){
        e.preventDefault();
        $(this).closest('.storyWrapper').find('.storyShare').fadeOut().removeClass('active');
    });

    $(document).on("click", function(e) {
        if (!$(e.target).closest('.storyShare, .storyToggle').length) {
            $('.storyShare').fadeOut().removeClass('active');
        }
    });

    if ($(window).width() < 767) {
        $('.storyToggle .text').text('Share');
    } else {
        $('.storyToggle .text').text('Share this story');
    }
    // ================================ Single Blog Story  ===================== //

    // ================================ noAjaxFilter blog-boxes  ===================== // 
    $('.noAjaxFilter .postFilterResult .blogItem:gt(3)').hide();
    $('.noAjaxFilter #loadMoreBtn').click(function() {
        var link = $(this);

        $('.noAjaxFilter .postFilterResult .blogItem:gt(3)').slideToggle('slow', function() {
            if ($(this).is(':visible')) {
                link.text('View Less');
            } else {
                link.text('View More');
            }
        });
    });
    // ================================ noAjaxFilter blog-boxes  ===================== //

    //  =============================== Form Enquire Cookie ====================================
    $(document).on('click', '.enquiryNow', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let img = $(this).parents('body').find('.expertAdvice .teamImg img').attr('src');
        if (img == undefined) {
            img = $themeUrl + '/images/Graham-Carter.jpg';
        }
        let postTitle = $(this).attr('data-postTitle');
        let postID = $(this).attr('data-postID');
        let departureDate = $(this).attr('data-departureDate') ?? '';
        let departureEvent = $(this).attr('data-departureEvent') ?? '';
        console.log(postTitle, postID, departureDate, departureEvent);

        if ($('.cst-breadcrumbs').length) {
            let brcLength = jQuery('.cst-breadcrumbs').find('li').length,
                sl = brcLength - 1,
                regionText = jQuery('.cst-breadcrumbs').find('li:nth-last-child(' + sl + ')').find("a").length ?
                jQuery('.cst-breadcrumbs').find('li:nth-last-child(' + sl + ')').find('a').text() :
                jQuery('.cst-breadcrumbs').find('li:nth-last-child(' + sl + ')').find('span').text();
            setCookie('reigon_name', regionText);
            sessionStorage.setItem('reigon_name', regionText);
        }
        
        setCookie('enquiryTitle', postTitle);
        setCookie('enquiryProdId', postID);
        setCookie('authorImg', img);
        setCookie('departureDate', departureDate);
        setCookie('departureEvent', departureEvent);
        /** Save Data in  Session Storage */
        sessionStorage.setItem('enquiryTitle', postTitle)
        sessionStorage.setItem('enquiryProdId', postID)
        sessionStorage.setItem('authorImg', img)
        sessionStorage.setItem('departureDate', departureDate);
        sessionStorage.setItem('departureEvent', departureEvent);
        /** Save Data in  Session Storage */
        setTimeout(function(){
            window.location.href = url;
        },500)
    });

    // Product Departure Date
    let departureDate = getCookie('departureDate') || sessionStorage.getItem('departureDate');
    if(departureDate){
        $('#input_2_73').val(departureDate).trigger('input').trigger('change');
    }

    // Product Departure Event
    let departureEvent = getCookie('departureEvent') || sessionStorage.getItem('departureEvent');
    if(departureEvent){
        let match = departureEvent.match(/\d+\s*Night(s)?/i);
        if (match) {
            let selectedNight = match[0].replace(/\bNight\b/i, 'Nights').toLowerCase().trim();
            $('#input_2_47 option').each(function () {
                let optionText = $(this).text().toLowerCase().trim();

                if (optionText === selectedNight) {
                    $('#input_2_47')
                        .val($(this).val())
                        .trigger('input')
                        .trigger('change');

                    return false; // loop break
                }
            });

        }
    }

    // fire cookie varible
    let usePostTitle = getCookie("enquiryTitle");
    let useEnquiryProdId = getCookie("enquiryProdId");
    let useAuthorImg = getCookie("authorImg");

    $(document).find('.headingWrapper .heading span.cookiesTitle').text(usePostTitle);
    if(useAuthorImg){
        $(document).find('.expertMember .authorImg .teamImg img.getCookiesImg').attr('src', useAuthorImg);
    }
    $(document).find('.reigon_name input[type="text"]').val(usePostTitle);

    // ON change country field
    $(document).on('change', '.currencyChanger select.gfield_select', function() {
        let value = $(this).val();
        console.log(value);
        let currency = $baseCurrency.toUpperCase();
        if (value == 'United Kingdom') {
            currency = 'GBP';
        }
        if (value == 'United States') {
            currency = 'USD';
        }
        if (value == 'Australia') {
            currency = 'AUD';
        }
        // Hidden Currency Field
        $('.getCurrencyChanger input[type="text"]').val(currency);

    });
    //  =============================== Form Enquire Cookie ====================================

    // ================================ Gravity Form Place Script  =====================
    jQuery(document).on('gform_post_render', function(event, form_id, current_page){
        if(jQuery('.place-title').length){
            if(form_id == 2 || form_id == 3){
                let reigonText =  getCookie("reigon_name"),
                    sessReigonText = sessionStorage.getItem('reigon_name'),
                    rt = reigonText == '' ? reigonText : sessReigonText;
                jQuery('.place-title').find('input').val(rt)
            }
        }
    });
    // ================================ Gravity Form Place Script  =====================

    // ================================ Calculate header height and remove banner content =====================
    
    if($(window).width() < 767 ){
        // let headerHeight = '';
        let headerHeightTransparent = '';

        if($(window).width() < 1024){
            // headerHeight = jQuery('.mobHeader.default').innerHeight() || 0;
            headerHeightTransparent = jQuery('.mobHeader.transparent').innerHeight() || 0;        
        }else{
            // headerHeight = jQuery('header.default').innerHeight() || 0;
            headerHeightTransparent = jQuery('header.transparent').innerHeight() || 0;
        }

        let sectionHeight = jQuery('.bannerSliderWrapper').innerHeight();
        // let contentHeight = jQuery('.pageBanner .itemDataWrapper .postionWrapper').innerHeight() || 0;


        let topValue = 0;
        if (headerHeightTransparent > 0) {
            let availableSpace = sectionHeight + headerHeightTransparent;
            topValue = availableSpace / 2;
            console.log(topValue);
            jQuery('.pageBanner .itemDataWrapper .postionWrapper').css({
                'top': topValue + 'px',
            });
        }
    }

    // ================================ Calculate header height and remove banner content =====================

    // ================================ Gallery FancyBox Script =====================
    Fancybox.destroy();
    // Bind ONLY non-slick gallery
    Fancybox.bind(
      'a[data-fancybox]:not(.slick-slide a[data-fancybox])',
      {}
    );
    if(jQuery('.slick-slide').length){
        jQuery(document).on(
          'click',
          '.slick-slide a[data-fancybox]',
          function (e) {

            e.preventDefault();

            const $slider = jQuery(this).closest('.slick-slider');

            const links = [];

            $slider.find(
              '.slick-slide:not(.slick-cloned) a[data-fancybox]'
            ).each(function () {

              const href = jQuery(this).attr('href');

              // only unique href
              if (href && links.indexOf(href) === -1) {

                links.push(href);

              }

            });

            const items = links.map(function (src) {

              return {
                src: src,
                type: 'image'
              };

            });

            const currentSrc = jQuery(this).attr('href');

            const startIndex = links.indexOf(currentSrc);

            Fancybox.show(items, {

              startIndex: startIndex,

              Thumbs: false,

              Carousel: {
                Thumbs: false
              },

              on: {
                destroy: () => {

                  $slider.slick('setPosition');

                }
              }

            });

          }
        );
    }
    
    // ================================ Gallery FancyBox Script =====================
    
    // ================================ Hotel Popup Script For Product page  =====================
    $('.hotelBox a.hotelPopup').on('click', function (e) {
        e.preventDefault();
        // Get Data
        let hotelTitle       = $(this).attr('hotelTitle');
        let hotelImages = JSON.parse(
            $(this).attr('hotelImages') || '[]'
        );
        let hotelHighlights = JSON.parse(
            $(this).attr('hotelHighlights') || '[]'
        );
        let hotelRatings = JSON.parse(
            $(this).attr('hotelRating') || '[]'
        );
        let hotelDescription = $(this).attr('hotelDescriptions');
        let hotelpermalink = $(this).attr('hotelpermalink');


        // Build Images HTML
        let imagesHtml = '';
        $.each(hotelImages, function (index, image) {
            imagesHtml += `
                <div class="hotelImgItem img-${index}">
                    <a href="${image}" data-fancybox="hotelPopupImg">
                        <img src="${image}" alt="img-${index}" loading="lazy" decoding="async">
                    </a>
                </div>
            `;
        });

        // Build Highlights HTML
        let highlightsHtml = '';
        $.each(hotelHighlights, function (index, item) {
            if(item.trim() !== '') {
                highlightsHtml += `<li>${item}</li>`;
            }
        });

        // Final hotelhighlights Data HTML
        let hotelhighlights = `
            <h3>Highlights</h3>
            <ul>
                ${highlightsHtml}
            </ul>
        `;

        // Append Data
        $('.popupTitle').html(hotelTitle);
        $('.popupHotelRating').html(hotelRatings);
        $('.popupHotelImages').html(imagesHtml);
        $('.hotelHighlights').html(hotelhighlights);
        $('.popupHotelData .hotelDesc .hotelCont').html(hotelDescription);
        $('.popupHotelData .hotelDesc .hotelbtn a').attr('href', hotelpermalink).html(hotelTitle);

        // Open Popup
        $('.ufgPopupWrapper').fadeIn();

        setTimeout(function () {
            let $slider = $('.popupHotelImages');
            if ($slider.find('.hotelImgItem').length > 1) {
                if ($slider.hasClass('slick-initialized')) {
                    $slider.slick('unslick');
                }
                $slider.empty();
                $slider.html(imagesHtml);
                initSlider($slider, {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    speed: 900,
                    infinite: false,
                    adaptiveHeight: true,
                    arrows: true,
                    prevArrow: '<div class="slick-arrow angel-arrow-left"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none"><path d="M1.19002 4.3438C1.86611 4.14308 2.63653 3.72105 3.25497 3.4174C3.68997 3.20124 4.09877 2.95419 4.48136 2.67113C5.4457 1.96089 6.23708 1.04478 6.78739 0H8.3754C8.3754 0 8.3754 0.0102934 8.3754 0.01544C7.70456 1.56459 6.59871 2.90273 5.20985 3.88574C4.89015 4.1122 4.57045 4.34894 4.21931 4.51879H15V5.48121H4.1669C4.52853 5.67164 4.87443 5.88266 5.20461 6.11426C6.59347 7.09727 7.69932 8.43541 8.37016 9.98456C8.37016 9.98456 8.37016 9.99485 8.37016 10H6.78215C6.23184 8.95008 5.43521 8.03911 4.47612 7.32887C4.09352 7.04581 3.67949 6.79876 3.24973 6.5826C2.63653 6.27895 2.04954 5.89295 1.36822 5.69223C0.131346 5.29079 0.000320435 5.02316 0.000320435 5.00772C0.000320435 4.99228 -0.0520887 4.76068 1.18478 4.35924L1.19002 4.3438Z" fill="#FCF4E7"></path></svg></div>',
                    nextArrow: '<div class="slick-arrow angel-arrow-right"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="10" viewBox="0 0 15 10" fill="none"><path d="M13.81 4.3438C13.1339 4.14308 12.3635 3.72105 11.745 3.4174C11.31 3.20124 10.9012 2.95419 10.5186 2.67113C9.5543 1.96089 8.76292 1.04478 8.21261 0H6.6246C6.6246 0 6.6246 0.0102934 6.6246 0.01544C7.29544 1.56459 8.40129 2.90273 9.79015 3.88574C10.1098 4.1122 10.4295 4.34894 10.7807 4.51879H0V5.48121H10.8331C10.4715 5.67164 10.1256 5.88266 9.79539 6.11426C8.40653 7.09727 7.30068 8.43541 6.62984 9.98456C6.62984 9.98456 6.62984 9.99485 6.62984 10H8.21785C8.76816 8.95008 9.56479 8.03911 10.5239 7.32887C10.9065 7.04581 11.3205 6.79876 11.7503 6.5826C12.3635 6.27895 12.9505 5.89295 13.6318 5.69223C14.8687 5.29079 14.9997 5.02316 14.9997 5.00772C14.9997 4.99228 15.0521 4.76068 13.8152 4.35924L13.81 4.3438Z" fill="#FCF4E7"></path></svg></div>',
                    responsive: [
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                    ]
                });
            }
            Fancybox.bind('.popupHotelImages a[data-fancybox="hotelPopupImg"]', {});
        }, 100);
        

    });
    // Close Popup
    $('.closePopup').on('click', function () {
        $('.ufgPopupWrapper').fadeOut();
    });
    // Outside click close
    $('.ufgPopupWrapper').on('click', function (e) {
        if ($(e.target).is('.ufgPopupWrapper')) {
            $('.ufgPopupWrapper').fadeOut();
        }
    });
    // ESC KEY CLOSE
    $(document).on('keyup', function (e) {
        if (e.key === "Escape") {
            $('.ufgPopupWrapper').fadeOut();
        }
    });
    // ================================ Hotel Popup Script For Product page  =====================

    // ================================ Blog With Filter JQuery  =====================
    let region = [];
    let interests = [];
    let paged = 1;

    /* ----------------------
    Helper to toggle Clear button visibility
    -----------------------*/
    function toggleClearButton() {
        if (region.length > 0 || interests.length > 0) {
            $(".clearFilters").fadeIn();
        } else {
            $(".clearFilters").fadeOut();
        }
    }

    /* ----------------------
    Toggle Filter
    -----------------------*/
    $('.toggleFilter').find('ul').hide();
    $(document).on("click", ".toggleFilter > span", function () {

        // sab filters close
        $('.toggleFilter > span').not(this).removeClass('active');
        $('.toggleFilter ul').not($(this).closest('.toggleFilter').find('ul')).slideUp();


        $(this).toggleClass('active');
        $(this).closest('.toggleFilter').find('ul').slideToggle();
    });

    /* ----------------------
    REGION click
    -----------------------*/
    $(document).on("click", ".regionFilter ul li", function () {

        paged = 1;

        $(".regionFilter ul li").removeClass("active");
        $(".regionFilter > span").removeClass("active");
        $(".regionFilter > ul").slideUp();

        $(this).addClass("active");
        region = [$(this).data("region")];
        let selected = $(this).text();
        $(".regionFilter .selected").text(selected);
        toggleClearButton();
        loadFilteredPosts();
    });

    /* ----------------------
    INTEREST click
    -----------------------*/
    $(document).on("click", ".interestFilter ul li", function () {

        paged = 1;

        $(".interestFilter ul li").removeClass("active");
        $(".interestFilter > span").removeClass("active");
        $(".interestFilter > ul").slideUp();
        $(this).addClass("active");
        interests = [$(this).data("interest")];
        let selected = $(this).text();
        $(".interestFilter .selected").text(selected);
        toggleClearButton();
        loadFilteredPosts();
    });

    /* ----------------------
    CLEAR ALL FILTERS
    -----------------------*/
    $(document).on('click', '.clearFilters', function () {

        paged = 1;

        $(".regionFilter ul li, .interestFilter ul li").removeClass("active");
        region = [];
        interests = [];
        // DEFAULT TEXT RESET
        $(".regionFilter .selected").text("Region");
        $(".interestFilter .selected").text("Interest");
        toggleClearButton();
        loadFilteredPosts();
    });

    /* ----------------------
    LOAD MORE button click
    -----------------------*/
    $(document).on("click", "#loadMoreBtn", function () {
        paged++;
        loadFilteredPosts(false); // append
    });

    /* ----------------------
    AJAX FUNCTION
    -----------------------*/
    function loadFilteredPosts(reset = true) {

        let containerHeight = $(".ajaxFilter .postFilterResult").height();
        if (containerHeight == 0) containerHeight = 600;

        $.ajax({
            url: kingdomVision.ajaxurl,
            type: "POST",
            data: {
                action: "filter_blog_posts",
                region: region,
                interests: interests,
                postPerPage: $('.ajaxFilter .postFilterResult').attr('data-page'),
                paged: paged
            },
            beforeSend: function () {
                if (reset) {
                    $(".ajaxFilter .postFilterResult").css("min-height", containerHeight + "px");
                    $(".ajaxFilter .postFilterResult").html("<div class='loaderWrapper'><div class='loader'></div></div>");
                } else {
                    $(".ajaxFilter .postFilterResult").append("<div class='loaderWrapper loadMoreLoader'><div class='loader'></div></div>");
                }
            },
            success: function (response) {

                if (reset) {
                    $(".ajaxFilter .postFilterResult").html(response.posts);
                    paged = 1;
                } else {
                    $(".ajaxFilter .postFilterResult").append(response.posts);
                }

                $(".ajaxFilter .postFilterResult").css("min-height", "auto");

                // SHOW/HIDE Load More button
                if (response.has_more) {
                    $("#loadMoreBtn").show();
                } else {
                    $("#loadMoreBtn").hide();
                }
            },
            complete: function () {
                $(".ajaxFilter .postFilterResult").css("min-height", "auto");
                $(".ajaxFilter .loaderWrapper.loadMoreLoader").remove();
                if (reset) {
                    $(".ajaxFilter .postFilterResult").find("> .loaderWrapper").remove();
                }
            }
        });
    }

    // ADD THIS TO LOAD POSTS ON PAGE LOAD
    loadFilteredPosts();
});
