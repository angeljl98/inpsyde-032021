jQuery(document).ready(function ($) {

    var ajaxurl = mate_pagination.ajax_url;

    function mate_is_on_screen(elem) {

        if ($(elem)[0]) {

            var tmtwindow = jQuery(window);
            var viewport_top = tmtwindow.scrollTop();
            var viewport_height = tmtwindow.height();
            var viewport_bottom = viewport_top + viewport_height;
            var tmtelem = jQuery(elem);
            var top = tmtelem.offset().top;
            var height = tmtelem.height();
            var bottom = top + height;
            return (top >= viewport_top && top < viewport_bottom) ||
                (bottom > viewport_top && bottom <= viewport_bottom) ||
                (height > viewport_height && top <= viewport_top && bottom >= viewport_bottom);
        }
    }

    var n = window.TWP_JS || {};
    var paged = parseInt(mate_pagination.paged) + 1;
    var maxpage = mate_pagination.maxpage;
    var nextLink = mate_pagination.nextLink;
    var loadmore = mate_pagination.loadmore;
    var loading = mate_pagination.loading;
    var nomore = mate_pagination.nomore;
    var pagination_layout = mate_pagination.pagination_layout;

    function mate_load_content_ajax(){
        
        if ((!$('.theme-no-posts').hasClass('theme-no-posts'))) {
            
            $('.theme-loading-button .loading-text').text(loading);
            $('.theme-loading-status').addClass('theme-ajax-loading');
            $('.theme-loaded-content').load(nextLink + ' .theme-article-panel', function () {
                if (paged < 10) {
                    var newlink = nextLink.substring(0, nextLink.length - 2);
                } else {

                    var newlink = nextLink.substring(0, nextLink.length - 3);
                }
                paged++;
                nextLink = newlink + paged + '/';
                if (paged > maxpage) {
                    $('.theme-loading-button').addClass('theme-no-posts');
                    $('.theme-loading-button .loading-text').text(nomore);
                } else {
                    $('.theme-loading-button .loading-text').text(loadmore);
                }
                var lodedContent = $('.theme-loaded-content').html();
                $('.theme-loaded-content').html('');

                $('.content-area .article-wraper').append(lodedContent);

                $('.theme-loading-status').removeClass('theme-ajax-loading');

                $('.theme-article.theme-article-panel').each(function () {

                    if (!$(this).hasClass('theme-article-loaded')) {

                        $(this).addClass(paged + '-theme-article-ajax');
                        $(this).addClass('theme-article-loaded');
                    }

                });

                // Background
                var pageSection = $(".data-bg");
                pageSection.each(function (indx) {
                    if ($(this).attr("data-background")) {
                        $(this).css("background-image", "url(" + $(this).data("background") + ")");
                    }
                });

            });

        }
    }

    $('.theme-loading-button').click(function () {

        mate_load_content_ajax();
        
    });

    if (pagination_layout == 'auto-load') {
        $(window).scroll(function () {

            if (!$('.theme-loading-status').hasClass('theme-ajax-loading') && !$('.theme-loading-button').hasClass('theme-no-posts') && maxpage > 1 && mate_is_on_screen('.theme-loading-button')) {
                
                mate_load_content_ajax();
                
            }

        });
    }

    $(window).scroll(function () {

        if (!$('.twp-single-infinity').hasClass('twp-single-loading') && $('.twp-single-infinity').attr('loop-count') <= 3 && mate_is_on_screen('.twp-single-infinity')) {

            $('.twp-single-infinity').addClass('twp-single-loading');
            var loopcount = $('.twp-single-infinity').attr('loop-count');
            var postid = $('.twp-single-infinity').attr('next-post');
            
            var data = {
                'action': 'mate_single_infinity',
                '_wpnonce': mate_pagination.ajax_nonce,
                'postid': postid,
            };

            $.post(ajaxurl, data, function (response) {
                
                if (response) {
                    var content = response.data.content.join('');
                    var content = $(content);
                    $('.twp-single-infinity').before(content);
                    var newpostid = response.data.postid.join('');
                    
                    $('.twp-single-infinity').attr('next-post', newpostid);

                    if ($('body').hasClass('booster-extension')) {
                        likedislike('after-load-ajax');
                        booster_extension_post_reaction('after-load-ajax-'+postid);
                    }

                    $('article#post-' + postid + ' ul.wp-block-gallery.columns-1, article#post-' + postid + ' .wp-block-gallery.columns-1 .blocks-gallery-grid, article#post-' + postid + ' .gallery-columns-1').each(function () {
                        $(this).slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            fade: true,
                            autoplay: true,
                            autoplaySpeed: 8000,
                            infinite: true,
                            nextArrow: '<button type="button" class="slide-btn slide-next-icon">'+mate_pagination.next_icon+'</button>',
                            prevArrow: '<button type="button" class="slide-btn slide-prev-icon">'+mate_pagination.prev_icon+'</button>',
                            dots: false
                        });
                    });


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

                }

                $('.twp-single-infinity').removeClass('twp-single-loading');
                loopcount++;
                $('.twp-single-infinity').attr('loop-count', loopcount);

            });

        }

    });

});