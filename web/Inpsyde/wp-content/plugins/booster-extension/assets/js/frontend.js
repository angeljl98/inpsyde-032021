
function twp_be_pinterest() {
    var e = document.createElement('script');
    e.setAttribute('type', 'text/javascript');
    e.setAttribute('charset', 'UTF-8');
    e.setAttribute('src', 'https://assets.pinterest.com/js/pinmarklet.js?r=' + Math.random() * 99999999);
    document.body.appendChild(e);
}

function twp_be_popup_new_window(event, url) {
    event.preventDefault();
    var strWindowFeatures = "location=yes,height=570,width=520,scrollbars=yes,status=yes";
    var LINK = url;
    window.open(LINK, "_blank", strWindowFeatures);
}

likedislike();

function likedislike(after_load = '') {

    jQuery(document).ready(function ($) {

        "use scrict";
        if (after_load) {
            var click_val = '.' + after_load + ' .twp-like-dislike-button .twp-post-like-dislike';
        } else {
            var click_val = '.twp-like-dislike-button .twp-post-like-dislike';
        }

        $(click_val).click(function () {

            var ajaxurl = booster_extension_frontend_script.ajax_url;
            var postID = $(this).attr('data-id');
            var LikeDislike = $(this).attr('id');

            if (LikeDislike == 'twp-post-like') {

                if ($(this).hasClass('cant-like')) {
                    var count_like = $(this).closest('.twp-like-dislike-button').find(".twp-like-count").html();
                    $(this).closest('.twp-like-dislike-button').find(".twp-like-count").html(count_like - 1);
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").removeClass('cant-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").addClass('can-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").removeClass('cant-dislike');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").addClass('can-dislike');
                }else{

                    if ($(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").hasClass('cant-dislike')) {
                        var count_dlike = $(this).closest('.twp-like-dislike-button').find(".twp-dislike-count").html();
                        $(this).closest('.twp-like-dislike-button').find(".twp-dislike-count").html(count_dlike - 1);
                    }
                    var count_like = $(this).closest('.twp-like-dislike-button').find(".twp-like-count").html();
                    count_like++;
                    $(this).closest('.twp-like-dislike-button').find(".twp-like-count").html(count_like);
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").removeClass('can-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").addClass('cant-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").removeClass('cant-dislike');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").addClass('can-dislike');
                }

            }else{

                if ($(this).hasClass('cant-dislike')) {
                    var count_dislike = $(this).closest('.twp-like-dislike-button').find(".twp-dislike-count").html();
                    $(this).closest('.twp-like-dislike-button').find(".twp-dislike-count").html(count_dislike - 1);
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").removeClass('cant-dislike');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").removeClass('cant-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").addClass('can-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").addClass('can-dislike');
                }else{

                    if ($(this).closest('.twp-like-dislike-button').find("#twp-post-like").hasClass('cant-like')) {
                        var count_like = $(this).closest('.twp-like-dislike-button').find(".twp-like-count").html();
                        $(this).closest('.twp-like-dislike-button').find(".twp-like-count").html(count_like - 1);
                    }

                    var count_dlike = $(this).closest('.twp-like-dislike-button').find(".twp-dislike-count").html();
                    count_dlike++;
                    $(this).closest('.twp-like-dislike-button').find(".twp-dislike-count").html(count_dlike);
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").removeClass('can-dislike');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").removeClass('cant-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-like").addClass('can-like');
                    $(this).closest('.twp-like-dislike-button').find("#twp-post-dislike").addClass('cant-dislike');

                }

            }

            var data = {
                'action': 'booster_extension_like_dislike',
                'postID': postID,
                'LikeDislike': LikeDislike,
                '_wpnonce': booster_extension_frontend_script.ajax_nonce,
            };

            $.post(ajaxurl, data, function (response) {
            });

        });

    });

}

booster_extension_post_reaction();

function booster_extension_post_reaction(after_load_reaction = '') {

    jQuery(document).ready(function ($) {

        "use scrict";
        if (after_load_reaction) {
            var click_val = '.' + after_load_reaction + ' .twp-reactions-icons .be-face-icons';
        } else {
            var click_val = '.twp-reactions-icons .be-face-icons';
        }
        $(click_val).click(function () {
            if ($(this).hasClass('reacted')) {

                var count_react = $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text();
                $(this).closest('.twp-reacts-wrap').find('.twp-react-count').empty();
                $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text(count_react - 1);
                $(this).addClass('un-reacted');
                $(this).removeClass('reacted');

                var percent = 0;
                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap').each(function () {
                    percent += Number($(this).closest('.twp-reacts-wrap').find('.twp-react-count').text());
                });

                var percent_4 = 0;
                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap').each(function () {
                    percent_4 += Number($(this).closest('.twp-reacts-wrap').find('.twp-react-count').text());
                });

                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap').each(function () {

                    var percent_5 = $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text();
                    var percent_6 = (percent_5 * 100) / parseInt(percent_4);
                    percent_6 = percent_6.toFixed(2);
                    $(this).closest('.twp-reacts-wrap').find('.twp-react-percent span').empty();
                    if (!percent_6) {
                        percent_6 = 0;
                    }
                    $(this).closest('.twp-reacts-wrap').find('.twp-react-percent span').text(percent_6);

                });

            } else {

                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap').each(function () {

                    if ($(this).find('.be-face-icons').hasClass('reacted')) {
                        var count_react_1 = $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text();
                        $(this).closest('.twp-reacts-wrap').find('.twp-react-count').empty();
                        $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text(count_react_1 - 1);
                    }

                });

                var count_react = $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text();
                $(this).closest('.twp-reacts-wrap').find('.twp-react-count').empty();
                count_react++;
                $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text(count_react);
                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap .be-face-icons').not(this).removeClass('reacted');
                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap .be-face-icons').not(this).addClass('un-reacted');
                $(this).addClass('reacted');
                $(this).removeClass('un-reacted');

                var percent_1 = 0;
                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap').each(function () {

                    percent_1 += Number($(this).closest('.twp-reacts-wrap').find('.twp-react-count').text());

                });

                $(this).closest('.twp-reactions-icons').find('.twp-reacts-wrap').each(function () {

                    var percent_2 = $(this).closest('.twp-reacts-wrap').find('.twp-react-count').text();
                    var percent_3 = (percent_2 * 100) / parseInt(percent_1);
                    percent_3 = percent_3.toFixed(0);
                    $(this).closest('.twp-reacts-wrap').find('.twp-react-percent span').empty();
                    if (!percent_3) {
                        percent_3 = 0;
                    }
                    
                    $(this).closest('.twp-reacts-wrap').find('.twp-react-percent span').text(percent_3);

                });

            }

            var ajaxurl = booster_extension_frontend_script.ajax_url;
            var reactdata = $(this).attr('react-data');
            var postID = $(this).attr('post-id');
            var data = {
                'action': 'booster_extension_post_react',
                'postID': postID,
                'reactdata': reactdata,
                '_wpnonce': booster_extension_frontend_script.ajax_nonce,
            };
            $.post(ajaxurl, data, function (response) {
            });

        });
    });
}

jQuery(document).ready(function ($) {

        "use scrict";

        $(".twp-review-link").click(function() {

            $('html, body').animate( { scrollTop: $("#comment").offset().top }, 400 );
            
        });

        $('.booster-bg-image').each(function () {
            var src = $(this).children('img').attr('src');
            $(this).css('background-image', 'url(' + src + ')').children('img').hide();
        });
});