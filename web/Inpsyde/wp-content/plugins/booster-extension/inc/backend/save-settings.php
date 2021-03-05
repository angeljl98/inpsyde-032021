<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$twp_be_settings = array();
$twp_social_share_options = explode( ',', sanitize_text_field( $_POST[ 'twp_social_share_options' ] ) );
$social_share_array = array();
foreach ( $twp_social_share_options as $social_share ) {
    $social_share_array[ $social_share ] = ( isset( $_POST[ 'social_share' ][ $social_share ] ) ) ? 1 : 0;
}
$twp_be_settings[ 'social_share' ] 								= $social_share_array;
$twp_be_settings[ 'social_share_ed_socila_counter' ]  			= isset( $_POST[ 'social_share_ed_socila_counter' ] ) ? sanitize_text_field( $_POST[ 'social_share_ed_socila_counter' ] ) : '';
$twp_be_settings[ 'social_share_ed_post' ]  					= isset( $_POST[ 'social_share_ed_post' ] ) ? sanitize_text_field( $_POST[ 'social_share_ed_post' ] ) : '';
$twp_be_settings[ 'social_share_ed_before_content' ]  					= isset( $_POST[ 'social_share_ed_before_content' ] ) ? sanitize_text_field( $_POST[ 'social_share_ed_before_content' ] ) : '';
$twp_be_settings[ 'social_share_ed_archive' ]  					= isset( $_POST[ 'social_share_ed_archive' ] ) ? sanitize_text_field( $_POST[ 'social_share_ed_archive' ] ) : '';
$twp_be_settings[ 'twp_be_show_author_box' ]  					= isset( $_POST[ 'twp_be_show_author_box' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_author_box' ] ) : '';
$twp_be_settings[ 'twp_be_show_author_email' ]  				= isset( $_POST[ 'twp_be_show_author_email' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_author_email' ] ) : '';
$twp_be_settings[ 'twp_be_show_author_role' ]  					= isset( $_POST[ 'twp_be_show_author_role' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_author_role' ] ) : '';
$twp_be_settings[ 'twp_be_show_author_title' ]  				= isset( $_POST[ 'twp_be_show_author_title' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_author_title' ] ) : '';
$twp_be_settings[ 'twp_be_show_author_alignmrnt' ]  			= isset( $_POST[ 'twp_be_show_author_alignmrnt' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_author_alignmrnt' ] ) : '';
$twp_be_settings[ 'twp_be_show_author_image_layout' ]  			= isset( $_POST[ 'twp_be_show_author_image_layout' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_author_image_layout' ] ) : '';
$twp_be_settings[ 'twp_be_open_link_type' ]  					= isset( $_POST[ 'twp_be_open_link_type' ] ) ? sanitize_text_field( $_POST[ 'twp_be_open_link_type' ] ) : '';
$twp_be_settings[ 'social_share_email_subject' ]  				= isset( $_POST[ 'social_share_email_subject' ] ) ? sanitize_text_field( $_POST[ 'social_share_email_subject' ] ) : '';
$twp_be_settings[ 'social_share_email_body' ]  					= isset( $_POST[ 'social_share_email_body' ] ) ? sanitize_text_field( $_POST[ 'social_share_email_body' ] ) : '';
$twp_be_settings[ 'twp_be_show_author_url' ]  					= isset( $_POST[ 'twp_be_show_author_url' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_author_url' ] ) : '';
$twp_be_settings[ 'twp_be_enable_like_dislike_button' ]  		= isset( $_POST[ 'twp_be_enable_like_dislike_button' ] ) ? sanitize_text_field( $_POST[ 'twp_be_enable_like_dislike_button' ] ) : '';
$twp_be_settings[ 'twp_be_show_like_dislike_on_single_post' ]  	= isset( $_POST[ 'twp_be_show_like_dislike_on_single_post' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_like_dislike_on_single_post' ] ) : '';
$twp_be_settings[ 'twp_be_show_like_dislike_on_archive' ]  		= isset( $_POST[ 'twp_be_show_like_dislike_on_archive' ] ) ? sanitize_text_field( $_POST[ 'twp_be_show_like_dislike_on_archive' ] ) : '';
$twp_be_settings[ 'twp_be_enable_read_time' ]  					= isset( $_POST[ 'twp_be_enable_read_time' ] ) ? sanitize_text_field( $_POST[ 'twp_be_enable_read_time' ] ) : '';
$twp_be_settings[ 'twp_be_readtime_word_per_minute' ]  			= isset( $_POST[ 'twp_be_readtime_word_per_minute' ] ) ? sanitize_text_field( $_POST[ 'twp_be_readtime_word_per_minute' ] ) : '';
$twp_be_settings[ 'twp_be_readtime_label' ]  			= isset( $_POST[ 'twp_be_readtime_label' ] ) ? sanitize_text_field( $_POST[ 'twp_be_readtime_label' ] ) : '';
$twp_be_settings[ 'twp_be_enable_second' ]  			= isset( $_POST[ 'twp_be_enable_second' ] ) ? sanitize_text_field( $_POST[ 'twp_be_enable_second' ] ) : '';
$twp_be_settings[ 'twp_be_second_label' ]  			= isset( $_POST[ 'twp_be_second_label' ] ) ? sanitize_text_field( $_POST[ 'twp_be_second_label' ] ) : '';
$twp_be_settings[ 'twp_be_minute_label' ]  			= isset( $_POST[ 'twp_be_minute_label' ] ) ? sanitize_text_field( $_POST[ 'twp_be_minute_label' ] ) : '';
$twp_be_settings[ 'social_share_fb_app_id' ]  					= isset( $_POST[ 'social_share_fb_app_id' ] ) ? sanitize_text_field( $_POST[ 'social_share_fb_app_id' ] ) : '';
$twp_be_settings[ 'social_share_fb_secret_key' ]  				= isset( $_POST[ 'social_share_fb_secret_key' ] ) ? sanitize_text_field( $_POST[ 'social_share_fb_secret_key' ] ) : '';
$twp_be_settings[ 'social_share_title' ]  						= isset( $_POST[ 'social_share_title' ] ) ? sanitize_text_field( $_POST[ 'social_share_title' ] ) : '';
$twp_be_settings[ 'twp_be_like_icon_layout' ]  					= isset( $_POST[ 'twp_be_like_icon_layout' ] ) ? sanitize_text_field( $_POST[ 'twp_be_like_icon_layout' ] ) : '';
$twp_be_settings[ 'twp_be_enable_post_reaction' ]  				= isset( $_POST[ 'twp_be_enable_post_reaction' ] ) ? sanitize_text_field( $_POST[ 'twp_be_enable_post_reaction' ] ) : '';
$twp_be_settings[ 'twp_be_react_percent_count' ]  				= isset( $_POST[ 'twp_be_react_percent_count' ] ) ? sanitize_text_field( $_POST[ 'twp_be_react_percent_count' ] ) : '';
$twp_be_settings[ 'twp_enable_post_rating' ]  				= isset( $_POST[ 'twp_enable_post_rating' ] ) ? sanitize_text_field( $_POST[ 'twp_enable_post_rating' ] ) : '';
$twp_be_settings[ 'twp_be_enable_post_visit_tracking' ]  				= isset( $_POST[ 'twp_be_enable_post_visit_tracking' ] ) ? sanitize_text_field( $_POST[ 'twp_be_enable_post_visit_tracking' ] ) : '';
$twp_be_settings[ 'twp_be_views_label' ]  				= isset( $_POST[ 'twp_be_views_label' ] ) ? sanitize_text_field( $_POST[ 'twp_be_views_label' ] ) : '';
// Update Option.
$status = update_option( 'twp_be_options_settings', $twp_be_settings );
if ( $status == TRUE ) {
    wp_redirect( admin_url() . 'admin.php?page=booster-extension' );
} else {
    wp_redirect( admin_url() . 'admin.php?page=booster-extension' );
}
exit;