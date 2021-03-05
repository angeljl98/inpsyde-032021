window.addEventListener("load", function(){
        
    jQuery(document).ready(function($){
        "use scrict";

        $("body").addClass("page-loaded");

    });

});

jQuery(document).ready(function ($) {
    "use strict";

    // Hide Comments
    $('.mate-no-comment .booster-block.booster-ratings-block, .mate-no-comment .comment-form-ratings, .mate-no-comment .twp-star-rating').hide();

    // Scroll To
    $(".scroll-content").click(function () {
        $('html, body').animate({
            scrollTop: $("#site-content").offset().top
        }, 500);
    });

    // Rating disable
    if (mate_custom.single_post == 1 && mate_custom.mate_ed_post_reaction) {
        $('.tpk-single-rating').remove();
        $('.tpk-comment-rating-label').remove();
        $('.comments-rating').remove();
        $('.tpk-star-rating').remove();
    }

    // Aub Menu Toggle
    $('.submenu-toggle').click(function () {
        $(this).toggleClass('button-toggle-active');
        var currentClass = $(this).attr('data-toggle-target');
        $(currentClass).toggleClass('submenu-toggle-active');
    });

    // Toggle Search
    $('.navbar-control-search').click(function () {
        $('.header-searchbar').toggleClass('header-searchbar-active');
        $('body').addClass('body-scroll-locked');
        $('#search-closer').focus();
    });

    $('.header-searchbar').click(function () {
        $('.header-searchbar').removeClass('header-searchbar-active');
        $('body').removeClass('body-scroll-locked');
    });

    $(".header-searchbar-inner").click(function (e) {
        e.stopPropagation(); //stops click event from reaching document
    });

    // Header Search hide
    $('#search-closer').click(function () {
        $('.header-searchbar').removeClass('header-searchbar-active');
        $('body').removeClass('body-scroll-locked');
        setTimeout(function () {
            $('.navbar-control-search').focus();
        }, 300);
    });

    // Focus on search input on search icon expand
    $('.navbar-control-search').click(function () {
        setTimeout(function () {
            $('.header-searchbar .search-field').focus();
        }, 300);
    });


    $('.skip-link-search-start').focus(function () {
        $('#search-closer').focus();
    });

    $('.skip-link-search-end').focus(function () {
        $('.header-searchbar .search-field').focus();
    });

    $('.skip-link-menu-start').focus(function () {

        if (!$("#offcanvas-menu #primary-nav-offcanvas").length == 0) {
            $("#offcanvas-menu #primary-nav-offcanvas ul li:last-child a").focus();
        }

        if (!$("#offcanvas-menu #social-nav-offcanvas").length == 0) {
            $("#offcanvas-menu #social-nav-offcanvas ul li:last-child a").focus();
        }

    });

    $(document).keyup(function (j) {
        
        if (j.key === "Escape") { // escape key maps to keycode `27`


            if ($('.header-searchbar').hasClass('header-searchbar-active')) {

                $('.header-searchbar').removeClass('header-searchbar-active');
                
                setTimeout(function () {
                    $('.navbar-control-search').focus();
                }, 300);

                setTimeout(function () {
                    $('.aside-search-js').focus();
                }, 300);

            }

            $('body').removeClass('body-scroll-locked');
            
        }
    });

    // Action On Esc Button
    $(document).keyup(function (j) {
        if (j.key === "Escape") { // escape key maps to keycode `27`
            if ($('#offcanvas-menu').hasClass('offcanvas-menu-active')) {
                $('.header-searchbar').removeClass('header-searchbar-active');
                $('#offcanvas-menu').removeClass('offcanvas-menu-active');
                $('.navbar-control-offcanvas').removeClass('active');
                $('body').removeClass('body-scroll-locked');
                setTimeout(function () {
                    $('.navbar-control-offcanvas').focus();
                }, 300);
            }
        }
    });

    // Toggle Menu
    $('.navbar-control-offcanvas').click(function () {
        $(this).addClass('active');
        $('body').addClass('body-scroll-locked');
        $('#offcanvas-menu').toggleClass('offcanvas-menu-active');
        $('.button-offcanvas-close').focus();
    });

    $('.offcanvas-close .button-offcanvas-close').click(function () {

        $('#offcanvas-menu').removeClass('offcanvas-menu-active');
        $('.navbar-control-offcanvas').removeClass('active');
        $('body').removeClass('body-scroll-locked');

        setTimeout(function () {
            $('.navbar-control-offcanvas').focus();
        }, 300);

    });

    $('#offcanvas-menu').click(function () {

        $('#offcanvas-menu').removeClass('offcanvas-menu-active');
        $('.navbar-control-offcanvas').removeClass('active');
        $('body').removeClass('body-scroll-locked');

    });

    $(".offcanvas-wraper").click(function (e) {

        e.stopPropagation(); //stops click event from reaching document

    });

    $('.skip-link-menu-end').on('focus', function () {

        $('.button-offcanvas-close').focus();

    });

    // Sticky Sidebar
    $('.widget-area').theiaStickySidebar({
        additionalMarginTop: 30
    });

    // Content Gallery start

    $("ul.wp-block-gallery.columns-1, .wp-block-gallery.columns-1 .blocks-gallery-grid, .gallery-columns-1").each(function () {

        $(this).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            autoplay: false,
            autoplaySpeed: 8000,
            infinite: true,
            nextArrow: '<button type="button" class="slide-btn slide-next-icon">'+mate_pagination.next_icon+'</button>',
            prevArrow: '<button type="button" class="slide-btn slide-prev-icon">'+mate_pagination.prev_icon+'</button>',
            dots: false,
        });

    });

    // Content Gallery End

    // Content Gallery popup Start
    $('.entry-content .gallery, .widget .gallery, .wp-block-gallery, .zoom-gallery').each(function () {

        $(this).magnificPopup({

            delegate: 'a',
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: false,
            mainClass: 'mfp-with-zoom mfp-img-mobile',
            image: {
                verticalFit: true,
                titleSrc: function (item) {
                    return item.el.attr('title');
                }
            },
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function (element) {
                    return element.find('img');
                }
            }

        });

    });

    // Content Gallery popup End

    
    var pageSection = $(".data-bg");
    pageSection.each(function (indx) {
        if ($(this).attr("data-background")) {
            $(this).css("background-image", "url(" + $(this).data("background") + ")");
        }
    });

    $(window).scroll(function () {

        if ($(window).scrollTop() > $(window).height() / 2) {

            $(".scroll-up").fadeIn(300);

        }else{

            $(".scroll-up").fadeOut(300);

        }
    });

    // Scroll to Top on Click
    $('.scroll-up').click(function () {

        $("html, body").animate({
            scrollTop: 0
        }, 700);

        return false;

    });

    // Widgets Tab

    $('.twp-nav-tabs .tab').on('click', function (event) {

        var tabid = $(this).attr('tab-data');
        $(this).closest('.tabbed-container').find('.tab').removeClass('active');
        $(this).addClass('active');
        $(this).closest('.tabbed-container').find('.tab-content .tab-pane').removeClass('active');
        $(this).closest('.tabbed-container').find('.content-' + tabid).addClass('active');

    });

    // Day Night Mode Start

    $('.navbar-day-night').on("click", function() {

        if( $(this).hasClass('navbar-day-on') ){

            $('html').removeClass('night-mode');
            var year = 1000 * 60 * 60 * 24 * 365;
            var expires = new Date((new Date()).valueOf() + year);
            document.cookie = "ThemeNightMode=true;expires=" + expires.toUTCString();

            $('html').addClass('night-mode');
            $('.navbar-day-night').addClass('navbar-night-on');
            $('.navbar-day-night').removeClass('navbar-day-on');
            $('.jl_en_day_night').addClass('options_dark_skin');
            $('.mobile_nav_class').addClass('wp-night-mode-on');

        }else{
            
            $('html').removeClass('night-mode');
            $('.navbar-day-night').addClass('navbar-day-on');
            $('.navbar-day-night').removeClass('navbar-night-on');
            $('.jl_en_day_night').removeClass('options_dark_skin');
            $('.mobile_nav_class').removeClass('wp-night-mode-on');

            var year = 1000 * 60 * 60 * 24 * 365;
            var expires = new Date((new Date()).valueOf() + year);
            document.cookie = "ThemeNightMode=false;expires=" + expires.toUTCString();
            

        }

    });
    if (document.cookie.indexOf('ThemeNightMode=true') == -1) {

        
        $('html').removeClass('night-mode');
        $('.navbar-day-night ').removeClass('navbar-night-on');
        $('.navbar-day-night ').addClass('navbar-day-on');

    }else{
        $('html').addClass('night-mode');
        $('.navbar-day-night ').removeClass('navbar-day-on');
        $('.navbar-day-night ').addClass('navbar-night-on');

    }

    // Day Night Mode End
    
    // Main banner
    $(".mainbanner-jumbotron").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        autoplay: true,
        autoplaySpeed: 8000,
        infinite: true,
        dots: false,
        arrows: false,
        asNavFor: '.jumbotron-pagenav'
    });
    $('.jumbotron-pagenav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.mainbanner-jumbotron',
        dots: false,
        arrows: false,
        focusOnSelect: true,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 2
                }
            },
        ]
    });

     $('.theme-carousel').slick({
        slidesToShow: 3,
        slidesToScroll: 3,
        autoplaySpeed: 8000,
        infinite: true,
        nextArrow: '<button type="button" class="slide-btn slide-next-icon">'+mate_pagination.next_icon+'</button>',
        prevArrow: '<button type="button" class="slide-btn slide-prev-icon">'+mate_pagination.prev_icon+'</button>',
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 21
                }
            }
        ]
    });
});

/* -----------------------------------------------------------------------------------------------
    Intrinsic Ratio Embeds
--------------------------------------------------------------------------------------------------- */
var mate = mate || {},
    $ = jQuery;
var $mate_doc = $(document),
    $mate_win = $(window),
    viewport = {};
viewport.top = $mate_win.scrollTop();
viewport.bottom = viewport.top + $mate_win.height();
mate.instrinsicRatioVideos = {
    init: function () {
        mate.instrinsicRatioVideos.makeFit();
        $mate_win.on('resize fit-videos', function () {
            mate.instrinsicRatioVideos.makeFit();
        });
    },
    makeFit: function () {
        var vidSelector = "iframe, object, video";
        $(vidSelector).each(function () {
            var $mate_video = $(this),
                $mate_container = $mate_video.parent(),
                mate_iTargetWidth = $mate_container.width();
            // Skip videos we want to ignore
            if ($mate_video.hasClass('intrinsic-ignore') || $mate_video.parent().hasClass('intrinsic-ignore')) {
                return true;
            }
            if (!$mate_video.attr('data-origwidth')) {
                // Get the video element proportions
                $mate_video.attr('data-origwidth', $mate_video.attr('width'));
                $mate_video.attr('data-origheight', $mate_video.attr('height'));
            }
            // Get ratio from proportions
            var mate_ratio = mate_iTargetWidth / $mate_video.attr('data-origwidth');
            // Scale based on ratio, thus retaining proportions
            $mate_video.css('width', mate_iTargetWidth + 'px');
            $mate_video.css('height', ($mate_video.attr('data-origheight') * mate_ratio) + 'px');
        });
    }
};
$mate_doc.ready(function () {
    mate.instrinsicRatioVideos.init();      // Retain aspect ratio of videos on window resize
});