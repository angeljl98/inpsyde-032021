<?php
/*
* Plugin Name: Booster Extension
* Version: 1.1.7
* Plugin URI: https://www.themeinwp.com/booster-extension/
* Description: Share your website urls in most popular social media platform and show share counts on your website with various designs. Calculate read time, singe post like and dislike counter, Author box with author details and reaction feature.
* Author: ThemeInWP
* Author URI: https://www.themeinwp.com/
* Requires at least: 4.5
* Tested up to: 5.6.2
* Text Domain: booster-extension
*
* @package Booster Extension
*/
// Exit if accessed directly.

if (!defined('ABSPATH')) {
    exit;
}

define('BOOSTER_EXTENSION_LANG_DIR', basename(dirname(__FILE__)) . '/languages/');
define( 'BOOSTER_EXTENSION_URL', plugin_dir_url( __FILE__ ) );

if (!class_exists('Booster_Extension_Class')) {

    class Booster_Extension_Class{

        function __construct(){

            if( isset( $_GET['page'] ) ){

                $current_page = $_GET['page'];
                add_action('in_admin_header', function () {

                  if ( !$current_page = 'booster-extension' ) return;

                  remove_all_actions('admin_notices');
                  remove_all_actions('all_admin_notices');

                }, 1000);
            }

            add_action('init', array($this, 'booster_extension_plugin_text_domain'));
            add_action( 'init', array( $this, 'booster_extension_comment' ),20 );
            register_activation_hook(__FILE__, array($this, 'twp_activation_default_value'));
            add_action('admin_enqueue_scripts', array($this, 'booster_extension_admin_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'booster_extension_frontend_scripts'));
            add_action('admin_menu', array($this, 'booster_extension_backend_menu'));
            add_action('admin_post_booster_extension_settings_options', array($this, 'booster_extension_settings_options'));
            add_filter('user_contactmethods', array($this, 'booster_extension_user_fields'));
            add_filter('body_class', array($this, 'booster_extension_body_class'));
            add_filter('the_content', array($this, 'booster_extension_frontend_the_content'));
            add_action('booster_extension_like_dislike', array($this, 'booster_extension_frontend_post_like_dislike'));
            add_shortcode('booster-extension-like-dislike', array($this, 'booster_extension_frontend_post_like_dislike_shortcode'));
            add_action('booster_extension_social_icons', array($this, 'booster_extension_frontend_social_share_action'));
            add_shortcode('booster-extension-ss', array($this, 'booster_extension_be_social_share_shortcode'));
            add_action('booster_extension_author_box', array($this, 'booster_extension_frontend_author_box'));
            add_shortcode('booster-extension-ab', array($this, 'booster_extension_frontend_author_box_shortcode'));
            add_action('booster_extension_read_time', array($this, 'booster_extension_frontend_read_time'));
            add_shortcode('booster-extension-read-time', array($this, 'booster_extension_frontend_read_time_shortcode'));
            add_action('booster_extension_reaction', array($this, 'booster_extension_frontend_reaction'));
            add_shortcode('booster-extension-reaction', array($this, 'booster_extension_frontend_reaction_shortcode'));
            include_once 'inc/backend/widget-base-class.php';
            include_once 'inc/backend/twp-be-author-widget.php';
            include_once 'inc/backend/twp-be-functions.php';
            include_once 'inc/backend/twp-be-like-dislike.php';
            include_once 'inc/backend/twp-be-post-reactions.php';
            include_once 'inc/backend/like-count-metabox.php';
            include_once 'inc/backend/user-field.php';

            include_once 'inc/backend/twp-be-views-count.php';
            add_shortcode( 'booster-extension-visit-count', 'booster_extension_get_post_view' );

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_post_visit_tracking = isset( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) : '';
                
                
            if( $twp_be_enable_post_visit_tracking ){

                add_action('booster_extension_post_view_action', array($this, 'booster_extension_post_view'));

            }

        }

        function booster_extension_post_view(){

            echo do_shortcode('[booster-extension-visit-count]');

        }
        // Body Class
        function booster_extension_body_class($classes){

            $classes[] = 'booster-extension';
            return $classes;

        }

        // Comment Rating
        function booster_extension_comment() {
            
            $twp_settings = get_option( 'twp_be_options_settings' );

            $twp_enable_post_rating = isset( $twp_settings[ 'twp_enable_post_rating' ] ) ? $twp_settings[ 'twp_enable_post_rating' ] : '1';

            $comment_filter = apply_filters( 'twp_enable_post_rating_filter', 1 );

            if( $twp_enable_post_rating && $comment_filter ){

                add_filter( 'comments_template', function ( $template ) {
                    return dirname(__FILE__) .'/template/comments.php';
                });
                
                include_once 'inc/backend/comment.php';

            }
            
        }

        // loads plugin text domain.
        function booster_extension_plugin_text_domain(){

            load_plugin_textdomain('booster-extension', false, BOOSTER_EXTENSION_LANG_DIR);

        }

        // Admin Script
        function booster_extension_admin_scripts(){

            wp_enqueue_script('booster-extension-admin', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), '', true);
            wp_enqueue_style('booster-extension-social-icons', plugin_dir_url(__FILE__) . 'assets/css/social-icons.min.css');
            wp_enqueue_style('booster-extension-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css');

            wp_localize_script( 
                'booster-extension-admin', 
                'booster_extension_admin',
                array(
                    'upload_image'   =>  esc_html__('Choose Image','booster-extension'),
                    'use_imahe'   =>  esc_html__('Select','booster-extension'),
                 )
            );

        }

        // Frontend Script
        function booster_extension_frontend_scripts(){

            wp_enqueue_script('booster-extension-frontend-script', plugin_dir_url(__FILE__) . 'assets/js/frontend.js', array('jquery'), '', true);
            wp_enqueue_style('booster-extension-social-icons', plugin_dir_url(__FILE__) . 'assets/css/social-icons.min.css');
            wp_enqueue_style('booster-extension-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
            $ajax_nonce = wp_create_nonce('be_ajax_nonce');
            wp_localize_script(
                'booster-extension-frontend-script',
                'booster_extension_frontend_script',
                array(
                    'ajax_url' => esc_url(admin_url('admin-ajax.php')),
                    'ajax_nonce' => $ajax_nonce,
                )
            );

        }

        // Add Backend Menu
        function booster_extension_backend_menu(){

            add_menu_page('Booster Extension', 'Booster Extension', 'manage_options', 'booster-extension', array($this, 'booster_extension_main_page'), 'dashicons-booster-extension');

        }

        // Settings Form
        function booster_extension_main_page(){

            include('inc/backend/main-page.php');

        }

        // Saving Form Values
        function booster_extension_settings_options(){

            if (isset($_POST['twp_options_nonce'], $_POST['twp_form_submit']) && wp_verify_nonce($_POST['twp_options_nonce'], 'twp_options_nonce') && current_user_can('manage_options')) {
                include('inc/backend/save-settings.php');
            } else {
                die('No script kiddies please!');
            }

        }

        // Add user social meta
        function booster_extension_user_fields($contact_methods){

            $contact_methods['twp_user_metabox_facebook'] = __('Facebook', 'booster-extension');
            $contact_methods['twp_user_metabox_twitter'] = __('Twitter', 'booster-extension');
            $contact_methods['twp_user_metabox_instagram'] = __('Instagram', 'booster-extension');
            $contact_methods['twp_user_metabox_pinterest'] = __('Pinterest', 'booster-extension');
            $contact_methods['twp_user_metabox_linkedin'] = __('LinkedIn', 'booster-extension');
            $contact_methods['twp_user_metabox_youtube'] = __('Youtube', 'booster-extension');
            $contact_methods['twp_user_metabox_vimeo'] = __('Vimeo', 'booster-extension');
            $contact_methods['twp_user_metabox_whatsapp'] = __('Whatsapp', 'booster-extension');
            $contact_methods['twp_user_metabox_github'] = __('Github', 'booster-extension');
            $contact_methods['twp_user_metabox_wordpress'] = __('WordPress', 'booster-extension');
            $contact_methods['twp_user_metabox_foursquare'] = __('FourSquare', 'booster-extension');
            $contact_methods['twp_user_metabox_vk'] = __('VK', 'booster-extension');
            $contact_methods['twp_user_metabox_twitch'] = __('Twitch', 'booster-extension');
            $contact_methods['twp_user_metabox_tumblr'] = __('Tumblr', 'booster-extension');
            $contact_methods['twp_user_metabox_snapchat'] = __('Snapchat', 'booster-extension');
            $contact_methods['twp_user_metabox_skype'] = __('Skype', 'booster-extension');
            $contact_methods['twp_user_metabox_reddit'] = __('Reddit', 'booster-extension');
            $contact_methods['twp_user_metabox_stackoverflow'] = __('Stack Overflow', 'booster-extension');
            $contact_methods['twp_user_metabox_xing'] = __('Xing', 'booster-extension');
            $contact_methods['twp_user_metabox_delicious'] = __('Delicious', 'booster-extension');
            $contact_methods['twp_user_metabox_soundcloud'] = __('SoundCloud', 'booster-extension');
            $contact_methods['twp_user_metabox_behance'] = __('Behance', 'booster-extension');
            $contact_methods['twp_user_metabox_steam'] = __('Steam', 'booster-extension');
            $contact_methods['twp_user_metabox_dribbble'] = __('Dribbble', 'booster-extension');
            $contact_methods['twp_user_metabox_blogger'] = __('Blogger', 'booster-extension');
            $contact_methods['twp_user_metabox_flickr'] = __('Flickr', 'booster-extension');
            $contact_methods['twp_user_metabox_spotify'] = __('spotify', 'booster-extension');
            $contact_methods['twp_user_metabox_rss'] = __('RSS', 'booster-extension');
            return $contact_methods;
        }

        // Update options on theme activate
        function twp_activation_default_value(){

            if (empty(get_option('twp_be_options_settings'))) {
                include('inc/backend/activation.php');
            }
        }

        // The Contetn Filter
        function booster_extension_frontend_the_content($content){

            if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) )
                return $content;
            if ( 'post' == get_post_type() ) {

                $beforecontent = '';
                $after_content = '';
                $all_content = '';

                
                $beforecontent .= "<div class='booster-block booster-read-block'>";
                $disable_readtime = apply_filters( 'booster_extension_filter_readtime_ed', 1 );
                if( $disable_readtime ){
                    $beforecontent .= do_shortcode('[booster-extension-read-time]');
                }

                $twp_be_settings = get_option('twp_be_options_settings');
                $twp_be_enable_post_visit_tracking = isset( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) : '';
                $social_share_ed_before_content = isset( $twp_be_settings[ 'social_share_ed_before_content' ] ) ? esc_html( $twp_be_settings[ 'social_share_ed_before_content' ] ) : '';

                if( $twp_be_enable_post_visit_tracking && is_singular('post') ){

                    $beforecontent .= booster_extension_set_post_view();

                    $disable_views = apply_filters( 'booster_extension_filter_views_ed', 1 );
                    if( $disable_views ){
                        $beforecontent .= do_shortcode('[booster-extension-visit-count]');
                    }
                }
                
                $beforecontent .= "</div>";

                $disable_like = apply_filters( 'booster_extension_filter_like_ed', 1 );

                if( $disable_like ){
                    $after_content .= do_shortcode('[booster-extension-like-dislike]');
                }

                $disable_ss = apply_filters( 'booster_extension_filter_ss_ed', 1 );
                if( $disable_ss ){
                    if( $social_share_ed_before_content ){
                        $beforecontent .= do_shortcode('[booster-extension-ss]');
                    }else{
                        $after_content .= do_shortcode('[booster-extension-ss]');
                    }
                }

                $disable_ab = apply_filters( 'booster_extension_filter_ab_ed', 1 );
                if( $disable_ab ){
                    $after_content .= do_shortcode('[booster-extension-ab]');
                }

                $disable_reaction = apply_filters( 'booster_extension_filter_reaction_ed', 1 );
                if( $disable_reaction ){
                    $after_content .= do_shortcode('[booster-extension-reaction]');
                }

                $beforecontent = apply_filters('booster_extemsion_content_before_filter',$beforecontent);
                $after_content = apply_filters('booster_extemsion_content_after_filter',$after_content);
                $all_content .= $beforecontent . $content . $after_content;

                $all_content = apply_filters('booster_extemsion_content_before_after',$all_content );
                
                return $all_content;

            } else {
                return $content;
            }
        }

        // Social Share
        function twp_frontend_social_share($args){

            $twp_be_settings = get_option('twp_be_options_settings');
            if (is_single() && !empty($twp_be_settings['social_share_ed_post'])) {
                $page_checked = true;
            } elseif (is_archive() && !empty($twp_be_settings['social_share_ed_archive'])) {
                $page_checked = true;
            } else {
                $page_checked = false;
            }
            if ($page_checked) {
                include('inc/frontend/social-share.php');
                $_POST["layout"] = '';
            }

        }

        // Social Share
        function booster_extension_frontend_social_share_action($args){

            $status = '';
            $layout = '';
            if ($args) {
                if (isset($args['layout'])) {
                    $_POST["layout"] = esc_html($args['layout']);
                    $layout = esc_html($args['layout']);
                }
                if (isset($args['status'])) {
                    $_POST["status"] = esc_html($args['status']);
                    $status = esc_html($args['status']);
                }
            }
            if (empty($status)) {
                $twp_be_settings = get_option('twp_be_options_settings');
                if (is_single() && !empty($twp_be_settings['social_share_ed_post'])) {
                    $status = 'enable';
                } elseif (is_archive() && !empty($twp_be_settings['social_share_ed_archive'])) {
                    $status = 'enable';
                } else {
                    $status = false;
                }
            }
            if ($status == 'enable') {
                include('inc/frontend/social-share.php');
                $_POST["layout"] = '';
                $_POST["status"] = '';
            }
        }

        // Social Share Shortcode
        function booster_extension_be_social_share_shortcode($args){

            ob_start();
            $status = '';
            $layout = '';
            if( $args ) {

                $_POST["layout"] = isset( $args[ 'layout' ] ) ? esc_html( $args[ 'layout' ] ) : '';
                $_POST["status"] = isset( $args[ 'status' ] ) ? esc_html( $args[ 'status' ] ) : '';
                $status = isset( $args[ 'status' ] ) ? esc_html( $args[ 'status' ] ) : '';

            }
            if (empty($status)) {
                $twp_be_settings = get_option('twp_be_options_settings');
                if (is_single() && !empty($twp_be_settings['social_share_ed_post'])) {
                    $status = 'enable';
                } elseif (is_archive() && !empty($twp_be_settings['social_share_ed_archive'])) {
                    $status = 'enable';
                } else {
                    $status = false;
                }
            }
            if ($status == 'enable') {

                include('inc/frontend/social-share.php');
                $_POST["layout"] = '';
                $_POST["status"] = '';

            }
            $html = ob_get_contents();
            ob_get_clean();
            return $html;

        }

        // Post Author Box
        function booster_extension_frontend_author_box(){

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_show_author_box = esc_html($twp_be_settings['twp_be_show_author_box']);
            if ( is_single() || is_archive()){
                include('inc/frontend/author-box.php');
            }
            

        }

        // Author Box Shortcode.
        function booster_extension_frontend_author_box_shortcode($userid){

            ob_start();
            $html = '';
            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_show_author_box = esc_html($twp_be_settings['twp_be_show_author_box']);

            if ( ( is_single() || is_archive() ) && $twp_be_show_author_box) {
                if (isset($userid['userid'])) {
                    $userid = $userid['userid'];
                    $_POST["userid"] = absint($userid);
                }
                include('inc/frontend/author-box-shortcode.php');
            }
            $html = ob_get_contents();
            ob_get_clean();
            return $html;

        }

        // Post Like Dislike.
        function booster_extension_frontend_post_like_dislike($allenable){

            include('inc/frontend/like-dislike.php');
            booster_extension_like_dislike_display($allenable);
        }

        // Post Like Dislike.
        function booster_extension_frontend_post_like_dislike_shortcode($allenable){

            include('inc/frontend/like-dislike.php');
            booster_extension_like_dislike_display($allenable);
        }

        // Read Time Calculate
        function booster_extension_frontend_read_time(){

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_read_time = esc_html($twp_be_settings['twp_be_enable_read_time']);
            include('inc/frontend/read-time.php');

        }

        // Read Time Calculate
        function booster_extension_frontend_read_time_shortcode(){

            ob_start();
            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_read_time = esc_html($twp_be_settings['twp_be_enable_read_time']);
            
            include('inc/frontend/read-time.php');

            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

        // Read Time Calculate
        function booster_extension_frontend_reaction(){

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_post_reaction = isset($twp_be_settings['twp_be_enable_post_reaction']) ? $twp_be_settings['twp_be_enable_post_reaction'] : '';
            if ($twp_be_enable_post_reaction) {
                include('inc/frontend/post-reactions.php');
            }
        }

        // Read Time Calculate
        function booster_extension_frontend_reaction_shortcode(){

            ob_start();
            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_enable_post_reaction = isset($twp_be_settings['twp_be_enable_post_reaction']) ? $twp_be_settings['twp_be_enable_post_reaction'] : '';
            if ($twp_be_enable_post_reaction) {
                include('inc/frontend/post-reactions.php');
            }
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

    }

    $GLOBALS['be_global'] = new Booster_Extension_Class();

}
