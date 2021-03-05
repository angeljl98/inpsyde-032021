<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$twp_be_settings = array();
$twp_be_settings[ 'social_share' ]  = array( 'facebook' => 1, 'twitter' => 1, 'pinterest' => 1, 'linkedin' => 1,'email' => 0, 'vk' => 0 ) ;
$twp_be_settings[ 'twp_be_show_author_title' ]  = esc_html__('About Post Author','booster-extension');
$twp_be_settings[ 'twp_be_show_author_alignmrnt' ]  = 'left';
$twp_be_settings[ 'twp_be_show_author_image_layout' ]  = 'square';
$twp_be_settings[ 'social_share_ed_post' ]  = 1;
$twp_be_settings[ 'social_share_ed_archive' ]  = 1;
$twp_be_settings[ 'social_share_ed_socila_counter' ]  = 1;
$twp_be_settings[ 'twp_be_show_author_email' ]  = 1;
$twp_be_settings[ 'twp_be_show_author_url' ]  = 1;
$twp_be_settings[ 'twp_be_show_author_box' ]  = 1;
$twp_be_settings[ 'twp_be_show_author_role' ]  = '';
$twp_be_settings[ 'twp_be_open_link_type' ]  = 'new-window';
$twp_be_settings[ 'social_share_email_subject' ]  = '';
$twp_be_settings[ 'social_share_email_body' ]  = esc_html__('Hey I Got Something For You','booster-extension');
$twp_be_settings[ 'twp_be_enable_like_dislike_button' ]  = 1;
$twp_be_settings[ 'twp_be_show_like_dislike_on_single_post' ]  = 1;
$twp_be_settings[ 'social_share_fb_app_id' ]  = '';
$twp_be_settings[ 'social_share_fb_secret_key' ]  = '';
$twp_be_settings[ 'social_share_title' ]  = esc_html__('Share','booster-extension');
$twp_be_settings[ 'twp_be_show_like_dislike_on_archive' ]  = 1;
$twp_be_settings[ 'twp_be_enable_read_time' ]  = 1;
$twp_be_settings[ 'twp_be_readtime_word_per_minute' ]  = '200';
$twp_be_settings[ 'twp_be_like_icon_layout' ]  = 'layout-1';
$twp_be_settings[ 'twp_be_enable_post_reaction' ]  = 1;
$twp_be_settings[ 'twp_be_enable_second' ]  = 1;
$twp_be_settings[ 'twp_enable_post_rating' ]  = 1;
$twp_be_settings[ 'twp_be_react_percent_count' ]  = 'percent';
$twp_be_settings[ 'twp_be_readtime_label' ]  = esc_html__('Read Time:','booster-extension');
$twp_be_settings[ 'twp_be_minute_label' ]  = esc_html__('Minute','booster-extension');
$twp_be_settings[ 'twp_be_second_label' ]  = esc_html__('Second','booster-extension');
$twp_be_settings[ 'twp_be_views_label' ]  = esc_html__('Views:','booster-extension');

update_option( 'twp_be_options_settings', $twp_be_settings );