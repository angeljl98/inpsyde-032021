<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Settings Widgets.
 *
 * @package Booster Extension
**/

$twp_be_settings = get_option( 'twp_be_options_settings' );
?>
<div class="twp-plugin-wrapper">
    <header class="twp-plugin-header">
        <h1><?php esc_html_e('Booster Extension','booster-extension'); ?></h1>
    </header>
    <div class="twp-plugin-content">
        <div class="twp-content-primary">
            <div class="main-wraper-options main-wraper-options-1 twp-block-panel">
                <div>
                    <p><?php esc_html_e("Booster Extension is a simple and lightweight WordPress plugin to help you supercharge your WordPress site. There’re numerous plugins in the WordPress repository, however, if you install them all, there’s inconsistency in their backend and frontend styles and possible plugin conflicts.",'booster-extension') ?></p>
                    <p><?php esc_html_e("That’s why we've created Booster Extension, essentials components for every WordPress blog or magazine.",'booster-extension') ?></p>
                </div>
                <div>
                    <h2><?php esc_html_e("Like this plugin?",'booster-extension') ?></h2>
                    <p>
                        <a href="<?php echo esc_url('https://wordpress.org/support/plugin/booster-extension/reviews/?filter=5'); ?>"><?php esc_html_e("Give it a 5 star rating",'booster-extension') ?></a> on WordPress.org.
                    </p>
                    <p>
                        <?php esc_html_e("Like and follow",'booster-extension') ?> <a href="<?php echo esc_url('https://themeinwp.com/'); ?>">ThemeinWP</a> on <a href="<?php echo esc_url('https://www.facebook.com/themeinwp/'); ?>">Facebook</a> & <a href="<?php echo esc_url('https://twitter.com/themeinwp'); ?>">Twitter</a>.
                    </p>
                </div>
            </div>
            <div class="main-wraper-options main-wraper-options-2 twp-block-panel">
                <form class="acft-plugin-settings-form" method="post" action="<?php echo esc_url( admin_url() . 'admin-post.php' )?>">
                    <input type="hidden" name="action" value="booster_extension_settings_options" />
                    <div class="twp-section-tab">
                        <ul class="twp-section-nav-tabs__list">
                            <li class="twp-section-nav-tabs">
                                <a id="twp-be-social-share" class="twp-be-tab twp-tab-active" href="javascript:void(0)"><?php esc_html_e('Social Share','booster-extension'); ?></a>
                            </li>
                            <li class="twp-section-nav-tabs">
                                <a id="twp-be-author-box" class="twp-be-tab" href="javascript:void(0)"><?php esc_html_e('Author Box','booster-extension'); ?></a>
                            </li>
                            <li class="twp-section-nav-tabs">
                                <a id="twp-be-like-dislike" class="twp-be-tab" href="javascript:void(0)"><?php esc_html_e('Post Like/Dislike','booster-extension'); ?></a>
                            </li>
                            <li class="twp-section-nav-tabs">
                                <a id="twp-be-read-time" class="twp-be-tab" href="javascript:void(0)"><?php esc_html_e('Read Time','booster-extension'); ?></a>
                            </li>
                            <li class="twp-section-nav-tabs">
                                <a id="twp-be-reactions" class="twp-be-tab" href="javascript:void(0)"><?php esc_html_e('Post Reactions','booster-extension'); ?></a>
                            </li>
                            <li class="twp-section-nav-tabs">
                                <a id="twp-be-track" class="twp-be-tab" href="javascript:void(0)"><?php esc_html_e('Posts Visit Count','booster-extension'); ?></a>
                            </li>
                            <li class="twp-section-nav-tabs">
                                <a id="twp-be-rating" class="twp-be-tab" href="javascript:void(0)"><?php esc_html_e('Posts Rating','booster-extension'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="twp-form-content">
                        <div id="twp-be-social-share-content" class="twp-be-content twp-content-active">
                            <div class="twp-be-options">
                                <h2 class="booster-control-title"><?php esc_html_e('Social Share Re Order And Enable/Disable','booster-extensiolns'); ?></h2>
                                <div class="twp-be-social-re-oprder">
                                    <?php
                                    $social_share_array = array(
                                        'facebook' => esc_html__('Facebook','booster-extensiolns'),
                                        'twitter' =>  esc_html__('Twitter','booster-extensiolns'),
                                        'pinterest' =>  esc_html__('Pinterest','booster-extensiolns'),
                                        'linkedin' =>  esc_html__('Linkedin','booster-extensiolns'),
                                        'email' =>  esc_html__('Email','booster-extensiolns'),
                                        'vk' =>  esc_html__('VK','booster-extensiolns'),
                                        
                                    );

                                    if( empty( $twp_be_settings[ 'social_share' ] ) ){
                                        $twp_be_settings[ 'social_share' ] = $social_share_array;
                                    }

                                    if( !array_key_exists('vk', $twp_be_settings[ 'social_share' ] ) ){
                                        $twp_be_settings[ 'social_share' ]['vk'] =  esc_html__('VK','booster-extensiolns');
                                    }

                                    $social_share_email_subject = isset( $twp_be_settings[ 'social_share_email_subject' ] ) ? $twp_be_settings[ 'social_share_email_subject' ] : '';
                                    $social_share_email_body = isset( $twp_be_settings[ 'social_share_email_body' ] ) ? $twp_be_settings[ 'social_share_email_body' ] : '';
                                    $social_share_fb_app_id = isset( $twp_be_settings[ 'social_share_fb_app_id' ] ) ? $twp_be_settings[ 'social_share_fb_app_id' ] : '';
                                    $social_share_fb_secret_key = isset( $twp_be_settings[ 'social_share_fb_secret_key' ] ) ? $twp_be_settings[ 'social_share_fb_secret_key' ] : '';
                                    
                                     foreach ( $twp_be_settings[ 'social_share' ] as $key => $value ) { ?>
                                        
                                        <div class="twp-be-social-share-wrap">
                                            <div class="twp-be-social-share-options">
                                                
                                                <div class="twp-toggle-control">
                                                    
                                                    <div class="twp-form-control">
                                                        <span class="dashicons dashicons-move twp-filter-icon"></span>
                                                        <input type="checkbox" data-key='<?php echo esc_attr( $key ); ?>' name="social_share[<?php echo esc_attr( $key ); ?>]" value="1" <?php if ( $value == '1' ) { echo "checked='checked'"; } ?> />
                                                        <label class="twp-social-network-name"><?php echo esc_html( $social_share_array[ $key ] ); ?></label>
                                                    </div>

                                                    <?php if( $key == 'email' || $key == 'facebook' || $key == 'twitter' ){ ?>
                                                        <div class="twp-toggle-icon">
                                                            <span class="dashicons dashicons-arrow-down twp-filter-icon"></span>
                                                        </div>
                                                    <?php } ?>

                                                </div>

                                                <?php if( $key == 'email' ){ ?>
                                                    <div style="display: none;" class="twp-social-control" id="twp-social-control-email">
                                                        
                                                        <div class="twp-opt-wrap twp-opt-wrap-alt">
                                                            <label><?php esc_html_e('Email Subject','booster-extension') ?></label>
                                                            <input type="text" name="social_share_email_subject" value="<?php echo esc_html( $social_share_email_subject ); ?>" />
                                                            
                                                        </div>
                                                        <div class="twp-opt-wrap twp-opt-wrap-alt">
                                                            <label><?php esc_html_e('Email Body','booster-extension') ?></label>
                                                            <textarea name="social_share_email_body" rows="10" cols="100"><?php echo  esc_html( $social_share_email_body ) ; ?></textarea>
                                                            
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php if( $key == 'facebook' ){ ?>
                                                    <div style="display: none;" class="twp-social-control" id="twp-social-control-facebook">
                                                        <div class="twp-opt-wrap twp-opt-wrap-alt">
                                                            <label><?php esc_html_e('App ID','booster-extension') ?></label>
                                                            <input type="text" name="social_share_fb_app_id" value="<?php echo esc_html( $social_share_fb_app_id ); ?>" />
                                                            
                                                        </div>
                                                        <div class="twp-opt-wrap twp-opt-wrap-alt">
                                                            <label><?php esc_html_e('App Secret Key','booster-extension') ?></label>
                                                            <input type="text" name="social_share_fb_secret_key" value="<?php echo esc_html( $social_share_fb_secret_key ); ?>" />
                                                            
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php if( $key == 'twitter' ){ ?>
                                                    <div style="display: none;" class="twp-social-control" id="twp-social-control-twitter">
                                                        <div class="twp-opt-wrap">
                                                            <label><?php esc_html_e('Please Register your website on Open Share Count to get twitter share count ','booster-extension'); ?>
                                                                <a href="<?php echo esc_url('http://opensharecount.com/'); ?>"><?php esc_html_e('Open Share Count.','booster-extension'); ?></a>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <input type="hidden" name="twp_social_share_options" id='twp_social_share_options' value="<?php echo implode( ',', array_keys( $twp_be_settings[ 'social_share' ] ) ); ?>"/>

                            </div>

                            <div class="twp-be-options">

                                <h2 class="booster-control-title">
                                    <span class="booster-core-icons dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Settings','booster-extension'); ?>
                                </h2>

                                <div class="twp-be-option-settings">

                                    <?php
                                    $social_share_ed_before_content = isset( $twp_be_settings[ 'social_share_ed_before_content' ] ) ? $twp_be_settings[ 'social_share_ed_before_content' ] : '';
                                    $social_share_title = isset( $twp_be_settings[ 'social_share_title' ] ) ? $twp_be_settings[ 'social_share_title' ] : '';
                                    $social_share_ed_post = isset( $twp_be_settings[ 'social_share_ed_post' ] ) ? $twp_be_settings[ 'social_share_ed_post' ] : '';
                                    $twp_be_open_link_type = isset( $twp_be_settings[ 'twp_be_open_link_type' ] ) ? $twp_be_settings[ 'twp_be_open_link_type' ] : '';
                                    $social_share_ed_archive = isset( $twp_be_settings[ 'social_share_ed_archive' ] ) ? $twp_be_settings[ 'social_share_ed_archive' ] : '';
                                    $social_share_ed_socila_counter = isset( $twp_be_settings[ 'social_share_ed_socila_counter' ] ) ? $twp_be_settings[ 'social_share_ed_socila_counter' ] : '';
                                    ?>
                                    <div class="twp-opt-wrap">
                                       
                                         <input type="checkbox" name="social_share_ed_before_content" <?php if( $social_share_ed_before_content ){ ?> checked="checked" <?php } ?> />
                                         <label><?php esc_html_e('Show Social Share before content','booster-extension') ?></label>
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Social Share Title','booster-extension'); ?></label>
                                        <input type="text" name="social_share_title" value="<?php echo esc_html( $social_share_title ); ?>" />
                                        
                                    </div>
                                    <div class="twp-opt-wrap">
                                       
                                         <input type="checkbox" name="social_share_ed_post" <?php if( !empty( $social_share_ed_post ) ){ ?> checked="checked" <?php } ?> />
                                         <label><?php esc_html_e('Show On Single Post','booster-extension') ?></label>
                                    </div>
                                    <div class="twp-opt-wrap">
                                        
                                        <input type="checkbox" name="social_share_ed_archive" <?php if( !empty( $social_share_ed_archive ) ){ ?> checked="checked" <?php } ?> />
                                        <label><?php esc_html_e('Show On Archive Page','booster-extension') ?></label>
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Open Share Link On','booster-extension') ?></label>
                                        <select name="twp_be_open_link_type">
                                            <option <?php if( $twp_be_open_link_type == 'same-window'){ echo 'selected'; } ?> value="same-window"><?php esc_html_e('Same Window','booster-extension') ?></option>
                                            <option <?php if( $twp_be_open_link_type == 'new-tab'){ echo 'selected'; } ?> value="new-tab"><?php esc_html_e('New Window Tab','booster-extension') ?></option>
                                            <option <?php if( $twp_be_open_link_type == 'new-window'){ echo 'selected'; } ?> value="new-window"><?php esc_html_e('New Window','booster-extension') ?></option>
                                        </select>
                                        
                                    </div>
                                    <div class="twp-opt-wrap">
                                    
                                        <input type="checkbox" name="social_share_ed_socila_counter" <?php if( !empty( $social_share_ed_socila_counter ) ){ ?> checked="checked" <?php } ?> />
                                        <label><?php esc_html_e('Show Social Share Count','booster-extension') ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="twp-be-options">
                                <h2 class="booster-control-title booster-control-title-alt">
                                    <span class="booster-help-icons dashicons dashicons-lightbulb"></span> <?php esc_html_e('How To Use','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <div class="twp-opt-wrap twp-opt-wrap-info">
                                        <label>
                                            <h4><?php esc_html_e("Shortcode","booster-extension"); ?></h4>
                                            <code>layouts: layout-1, layout-2
                                            <br>echo do_shortcode('[booster-extension-ss layout="layout-1" status="enable"]');
                                            </code>
                                            <br>
                                            <h4><?php esc_html_e("Hook",'booster-extension'); ?></h4>
                                            <code>
                                                $args = array('layout'=>'layout-2','status'=>'enable');
                                                <br>do_action('booster_extension_social_icons',$args);
                                            </code>
                                            <h4><?php esc_html_e("Filter",'booster-extension'); ?></h4>
                                            <?php esc_html_e("You can return false to hide Social Share after content by using below filter.",'booster-extension'); ?>
                                            <code>booster_extension_filter_ss_ed</code>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php

                        $twp_be_show_author_title = isset( $twp_be_settings[ 'twp_be_show_author_title' ] ) ? $twp_be_settings[ 'twp_be_show_author_title' ] : '';
                        $twp_be_show_author_box = isset( $twp_be_settings[ 'twp_be_show_author_box' ] ) ? $twp_be_settings[ 'twp_be_show_author_box' ] : '';
                        $twp_be_show_author_alignmrnt = isset( $twp_be_settings[ 'twp_be_show_author_alignmrnt' ] ) ? $twp_be_settings[ 'twp_be_show_author_alignmrnt' ] : '';
                        $twp_be_show_author_image_layout = isset( $twp_be_settings[ 'twp_be_show_author_image_layout' ] ) ? $twp_be_settings[ 'twp_be_show_author_image_layout' ] : '';
                        $twp_be_show_author_email = isset( $twp_be_settings[ 'twp_be_show_author_email' ] ) ? $twp_be_settings[ 'twp_be_show_author_email' ] : '';
                        $twp_be_show_author_url = isset( $twp_be_settings[ 'twp_be_show_author_url' ] ) ? $twp_be_settings[ 'twp_be_show_author_url' ] : '';
                        $twp_be_show_author_role = isset( $twp_be_settings[ 'twp_be_show_author_role' ] ) ? $twp_be_settings[ 'twp_be_show_author_role' ] : '';
                        ?>
                        <div id="twp-be-author-box-content" class="twp-be-content">
                            <div class="twp-be-options">
                                <h2 class="booster-control-title">
                                    <span class="booster-core-icons dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Settings','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <div class="twp-opt-wrap twp-opt-wrap-alt twp-opt-switch">
                                        <label><?php esc_html_e('Show Author Section','booster-extension') ?></label>
                                        <input id="booster-authorbox-checkbox" type="checkbox" name="twp_be_show_author_box" <?php if( !empty( $twp_be_show_author_box ) ){ ?> checked="checked" <?php } ?> />
                                        <label for="booster-authorbox-checkbox" class="twp-checkbox-label"></label>
                                    </div>
                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Widget Author Box Title','booster-extension') ?></label>
                                        <input type="text" name="twp_be_show_author_title" value="<?php echo esc_html( $twp_be_show_author_title ); ?>"  />
                                        
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">

                                        <label><?php esc_html_e('Alignment','booster-extension') ?></label>

                                        <select name="twp_be_show_author_alignmrnt">
                                            <option <?php if( $twp_be_show_author_alignmrnt == 'left'){ echo 'selected'; } ?> value="left"><?php esc_html_e('Left','booster-extension') ?></option>
                                            <option <?php if( $twp_be_show_author_alignmrnt == 'right'){ echo 'selected'; } ?> value="right"><?php esc_html_e('Right','booster-extension') ?></option>
                                            <option <?php if( $twp_be_show_author_alignmrnt == 'center'){ echo 'selected'; } ?> value="center"><?php esc_html_e('Center','booster-extension') ?></option>
                                        </select>
                                        
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Profile Image layout','booster-extension') ?></label>
                                        <select name="twp_be_show_author_image_layout">
                                            <option <?php if( $twp_be_show_author_image_layout == 'square'){ echo 'selected'; } ?> value="square"><?php esc_html_e('Square','booster-extension') ?></option>
                                            <option <?php if( $twp_be_show_author_image_layout == 'round'){ echo 'selected'; } ?> value="round"><?php esc_html_e('Round','booster-extension') ?></option>
                                        </select>
                                        
                                    </div>
                                    <div class="twp-opt-wrap">
                                        <input type="checkbox" name="twp_be_show_author_email" <?php if( !empty( $twp_be_show_author_email ) ){ ?> checked="checked" <?php } ?> />
                                        <label><?php esc_html_e('Show Author Email','booster-extension') ?></label>
                                        
                                    </div>
                                    <div class="twp-opt-wrap">
                                        <input type="checkbox" name="twp_be_show_author_url" <?php if( !empty( $twp_be_show_author_url ) ){ ?> checked="checked" <?php } ?> />
                                        <label><?php esc_html_e('Show Author URL','booster-extension') ?></label>
                                        
                                    </div>
                                    <div class="twp-opt-wrap">
                                        <input type="checkbox" name="twp_be_show_author_role" <?php if( !empty( $twp_be_show_author_role ) ){ ?> checked="checked" <?php } ?> />
                                        <label><?php esc_html_e('Show Author Role','booster-extension') ?></label>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="twp-be-options">
                                <h2 class="booster-control-title booster-control-title-alt">
                                    <span class="booster-help-icons dashicons dashicons-lightbulb"></span> <?php esc_html_e('How To Use','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <div class="twp-opt-wrap twp-opt-wrap-info">
                                        <label>
                                            <h4><?php esc_html_e("Shortcode",''); ?></h4>
                                            <code> echo do_shortcode('[booster-extension-ab userid="1"]');</code>
                                            <h4><?php esc_html_e("Hook",''); ?></h4>
                                            <code>do_action('booster_extension_author_box');</code>
                                            <h4><?php esc_html_e("Filter",''); ?></h4>
                                            <?php esc_html_e("You can return false to hide after content Author Box on single post by using below filter.",'booster-extension'); ?>
                                            <code>
                                                booster_extension_filter_ab_ed
                                            </code>
                                        </label>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $twp_be_enable_like_dislike_button = isset( $twp_be_settings[ 'twp_be_enable_like_dislike_button' ] ) ? $twp_be_settings[ 'twp_be_enable_like_dislike_button' ] : '';
                        $twp_be_show_like_dislike_on_single_post = isset( $twp_be_settings[ 'twp_be_show_like_dislike_on_single_post' ] ) ? $twp_be_settings[ 'twp_be_show_like_dislike_on_single_post' ] : '';
                        $twp_be_show_like_dislike_on_archive = isset( $twp_be_settings[ 'twp_be_show_like_dislike_on_archive' ] ) ? $twp_be_settings[ 'twp_be_show_like_dislike_on_archive' ] : '';
                        ?>
                        <div id="twp-be-like-dislike-content" class="twp-be-content">
                            <div class="twp-be-options">
                                <h2 class="booster-control-title">
                                    <span class="booster-core-icons dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Settings','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                        <div class="twp-opt-wrap twp-opt-wrap-alt twp-opt-switch">
                                            <label><?php esc_html_e('Enable Like/Dislike Button ','booster-extension') ?></label>
                                            <input type="checkbox" id="booster-like-checkbox" name="twp_be_enable_like_dislike_button" <?php if( !empty( $twp_be_enable_like_dislike_button ) ){ ?> checked="checked" <?php } ?> />
                                            <label for="booster-like-checkbox" class="twp-checkbox-label"></label>
                                        </div>
                                        <div class="twp-opt-wrap">
                                            <input type="checkbox" name="twp_be_show_like_dislike_on_single_post" <?php if( !empty( $twp_be_show_like_dislike_on_single_post ) ){ ?> checked="checked" <?php } ?> />
                                            <label><?php esc_html_e('Show Like/Dislike On Single Post','booster-extension') ?></label>
                                        </div>
                                        <div class="twp-opt-wrap">
                                            <input type="checkbox" name="twp_be_show_like_dislike_on_archive" <?php if( !empty( $twp_be_show_like_dislike_on_archive ) ){ ?> checked="checked" <?php } ?> />
                                            <label><?php esc_html_e('Show Like/Dislike On Archive Page','booster-extension') ?></label>
                                        </div>
                                        <?php
                                        $twp_be_like_icon_layout = isset( $twp_be_settings[ 'twp_be_like_icon_layout' ] ) ? $twp_be_settings['twp_be_like_icon_layout'] : 'layout-1'; ?>
                                        <div class="twp-opt-wrap twp-opt-wrap-alt">
                                            <label><?php esc_html_e('Like Icon Type','booster-extension') ?></label>
                                            <select name="twp_be_like_icon_layout">
                                                <option <?php if( $twp_be_like_icon_layout == 'layout-1' || $twp_be_like_icon_layout == '' ){ echo 'selected'; } ?> value="layout-1"><?php esc_html_e('Thumb Icon','booster-extension') ?></option>
                                                <option <?php if( $twp_be_like_icon_layout == 'layout-2'){ echo 'selected'; } ?> value="layout-2"><?php esc_html_e('Face Icon','booster-extension') ?></option>
                                            </select>
                                        </div>
                                </div>
                            </div>
                            <div class="twp-be-options">
                                <h2 class="booster-control-title booster-control-title-alt">
                                    <span class="booster-help-icons dashicons dashicons-lightbulb"></span> <?php esc_html_e('How To Use','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <div class="twp-opt-wrap twp-opt-wrap-info">
                                        <label>
                                            <h4><?php esc_html_e("Shortcode",'booster-extension'); ?></h4>
                                            <code> echo do_shortcode('[booster-extension-like-dislike]');</code>
                                            <h4><?php esc_html_e("Hook",'booster-extension'); ?></h4>
                                            <code> do_action('booster_extension_like_dislike');</code>
                                            <h4><?php esc_html_e("Filter",'booster-extension'); ?></h4>
                                            <?php esc_html_e("You can return false to hide after content Like Dislike button by using below filter.",'booster-extension'); ?>
                                            <code>
                                                booster_extension_filter_like_ed
                                            </code>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="twp-be-read-time-content" class="twp-be-content">

                            <?php
                            $twp_be_readtime_label = isset( $twp_be_settings[ 'twp_be_readtime_label' ] ) ? $twp_be_settings[ 'twp_be_readtime_label' ] : esc_html__('Read Time','booster-extension');
                            $twp_be_enable_second = isset( $twp_be_settings[ 'twp_be_enable_second' ] ) ? $twp_be_settings[ 'twp_be_enable_second' ] : 1;
                            $twp_be_minute_label = isset( $twp_be_settings[ 'twp_be_minute_label' ] ) ? $twp_be_settings[ 'twp_be_minute_label' ] : esc_html__('Minute','booster-extension');
                            $twp_be_second_label = isset( $twp_be_settings[ 'twp_be_second_label' ] ) ? $twp_be_settings[ 'twp_be_second_label' ] : esc_html__('Second','booster-extension');

                            $twp_be_enable_read_time = isset( $twp_be_settings[ 'twp_be_enable_read_time' ] ) ? $twp_be_settings[ 'twp_be_enable_read_time' ] : '';
                            $twp_be_readtime_word_per_minute = isset( $twp_be_settings[ 'twp_be_readtime_word_per_minute' ] ) ? $twp_be_settings[ 'twp_be_readtime_word_per_minute' ] : '';
                             ?>

                            <div class="twp-be-options">
                                <h2 class="booster-control-title">
                                    <span class="booster-core-icons dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Settings','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">

                                    <div class="twp-opt-wrap twp-opt-wrap-alt twp-opt-switch">
                                        <label><?php esc_html_e('Enable Read Time ','booster-extension'); ?></label>
                                        <input id="booster-readtime-checkbox" type="checkbox" name="twp_be_enable_read_time" <?php if( !empty( $twp_be_enable_read_time ) ){ ?> checked="checked" <?php } ?> />
                                        <label for="booster-readtime-checkbox" class="twp-checkbox-label"></label>
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt twp-opt-switch">
                                        <label><?php esc_html_e('Enable Second','booster-extension'); ?></label>
                                        <input id="booster-second-checkbox" type="checkbox" name="twp_be_enable_second" <?php if( !empty( $twp_be_enable_second ) ){ ?> checked="checked" <?php } ?> />
                                        <label for="booster-second-checkbox" class="twp-checkbox-label"></label>
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Readtime Words Per Minute','booster-extension') ?></label>
                                        <input type="text" name="twp_be_readtime_word_per_minute" value="<?php echo absint( $twp_be_readtime_word_per_minute ); ?>"  />
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Readtime Label','booster-extension') ?></label>
                                        <input type="text" name="twp_be_readtime_label" value="<?php echo esc_html( $twp_be_readtime_label ); ?>"  />
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Minute Label','booster-extension') ?></label>
                                        <input type="text" name="twp_be_minute_label" value="<?php echo esc_html( $twp_be_minute_label ); ?>"  />
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Second Label','booster-extension') ?></label>
                                        <input type="text" name="twp_be_second_label" value="<?php echo esc_html( $twp_be_second_label ); ?>"  />
                                    </div>

                                </div>
                            </div>
                            <div class="twp-be-options">
                                <h2 class="booster-control-title booster-control-title-alt">
                                    <span class="booster-help-icons dashicons dashicons-lightbulb"></span> <?php esc_html_e('How To Use','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <div class="twp-opt-wrap twp-opt-wrap-info">
                                        <label>
                                            <h4><?php esc_html_e("Shortcode",'booster-extension'); ?></h4>
                                            <code> echo do_shortcode('[booster-extension-read-time]');</code>
                                            <h4><?php esc_html_e("Hook",'booster-extension'); ?></h4>
                                            <code>do_action('booster_extension_read_time');</code>
                                            <h4><?php esc_html_e("Filter",'booster-extension'); ?></h4>
                                            <?php esc_html_e("You can return false to hide before content Read Time calculate by using below filter.",'booster-extension'); ?>
                                            <code>
                                                booster_extension_filter_readtime_ed
                                            </code>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="twp-be-reactions-content" class="twp-be-content">
                            <div class="twp-be-options">
                                <h2 class="booster-control-title">
                                    <span class="booster-core-icons dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Settings','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <?php 
                                    $twp_be_enable_post_reaction =  isset( $twp_be_settings[ 'twp_be_enable_post_reaction' ] ) ? $twp_be_settings['twp_be_enable_post_reaction'] : '1';
                                    ?>
                                    <div class="twp-opt-wrap twp-opt-wrap-alt twp-opt-switch">
                                        <label><?php esc_html_e('Enable Post Reactions','booster-extension'); ?></label>
                                        <input id="booster-reaction-checkbox" type="checkbox" name="twp_be_enable_post_reaction" <?php if( !empty( $twp_be_enable_post_reaction ) ){ ?> checked="checked" <?php } ?> />
                                        <label for="booster-reaction-checkbox" class="twp-checkbox-label"></label>
                                    </div>
                                </div>
                                <?php
                                $twp_be_react_percent_count = isset( $twp_be_settings[ 'twp_be_react_percent_count' ] ) ? $twp_be_settings['twp_be_react_percent_count'] : 'percent'; ?>
                                <div class="twp-opt-wrap twp-opt-wrap-alt">
                                    <label><?php esc_html_e('Reactions Progress Type','booster-extension') ?></label>
                                    <select name="twp_be_react_percent_count">
                                        <option <?php if( $twp_be_react_percent_count == 'percent' || $twp_be_react_percent_count == '' ){ echo 'selected'; } ?> value="percent"><?php esc_html_e('Percent','booster-extension') ?></option>
                                        <option <?php if( $twp_be_react_percent_count == 'number'){ echo 'selected'; } ?> value="number"><?php esc_html_e('Number','booster-extension') ?></option>
                          
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="twp-be-options">
                                <h2 class="booster-control-title booster-control-title-alt">
                                    <span class="booster-help-icons dashicons dashicons-lightbulb"></span> <?php esc_html_e('How To Use','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <div class="twp-opt-wrap twp-opt-wrap-info">
                                        <label>
                                            <h4><?php esc_html_e("Shortcode",'booster-extension'); ?></h4>
                                            <code> echo do_shortcode('[booster-extension-reaction]');</code>
                                            <h4><?php esc_html_e("Hook",'booster-extension'); ?></h4>
                                            <code>do_action('booster_extension_reaction');</code>
                                            <h4><?php esc_html_e("Filter",'booster-extension'); ?></h4>
                                            <?php esc_html_e("You can return false to hide after content Post Reaction by using below filter.",'booster-extension'); ?>
                                            <code>
                                                booster_extension_filter_reaction_ed
                                            </code>
                                        </label>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="twp-be-track-content" class="twp-be-content">
                            <div class="twp-be-options">
                                <h2 class="booster-control-title">
                                    <span class="booster-core-icons dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Settings','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <?php 
                                    $twp_be_enable_post_visit_tracking =  isset( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) ? $twp_be_settings['twp_be_enable_post_visit_tracking'] : '';
                                    $twp_be_views_label =  isset( $twp_be_settings[ 'twp_be_views_label' ] ) ? $twp_be_settings['twp_be_views_label'] : esc_html__('Views','booster-extension');
                                    ?>
                                    <div class="twp-opt-wrap twp-opt-wrap-alt twp-opt-switch">
                                        <label><?php esc_html_e('Enable Tracking Post Visit','booster-extension'); ?></label>
                                        <input id="booster-visit-checkbox" type="checkbox" name="twp_be_enable_post_visit_tracking" <?php if( $twp_be_enable_post_visit_tracking){ ?> checked="checked" <?php } ?> />
                                        <label for="booster-visit-checkbox" class="twp-checkbox-label"></label>
                                    </div>

                                    <div class="twp-opt-wrap twp-opt-wrap-alt">
                                        <label><?php esc_html_e('Views Label','booster-extension') ?></label>
                                        <input type="text" name="twp_be_views_label" value="<?php echo esc_html( $twp_be_views_label ); ?>"  />
                                    </div>

                                </div>
                            </div>

                            
                            <div class="twp-be-options">
                                <h2 class="booster-control-title booster-control-title-alt">
                                    <span class="booster-help-icons dashicons dashicons-lightbulb"></span> <?php esc_html_e('How To Use','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <div class="twp-opt-wrap twp-opt-wrap-info">
                                        <label>
                                            <h4><?php esc_html_e("Shortcode",'booster-extension'); ?></h4>
                                            <code> echo do_shortcode('[booster-extension-visit-count]');</code>
                                            <h4><?php esc_html_e("Hook",'booster-extension'); ?></h4>
                                            <code>
                                                booster_extension_post_view_action
                                            </code>
                                            <h4><?php esc_html_e("Filter",'booster-extension'); ?></h4>
                                            <code>
                                                booster_extension_filter_views_ed
                                            </code>
                                        </label>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="twp-be-rating-content" class="twp-be-content">
                            <div class="twp-be-options">
                                <h2 class="booster-control-title">
                                    <span class="booster-core-icons dashicons dashicons-admin-settings"></span> <?php esc_html_e('Display Settings','booster-extension'); ?>
                                </h2>
                                <div class="twp-be-option-settings">
                                    <?php 
                                    $twp_enable_post_rating =  isset( $twp_be_settings[ 'twp_enable_post_rating' ] ) ? $twp_be_settings['twp_enable_post_rating'] : '';
                                    ?>
                                    <div class="twp-opt-wrap twp-opt-wrap-alt twp-opt-switch">
                                        <label><?php esc_html_e('Enable Post Rating','booster-extension'); ?></label>
                                        <input id="booster-rating-checkbox" type="checkbox" name="twp_enable_post_rating" <?php if( $twp_enable_post_rating){ ?> checked="checked" <?php } ?> />
                                        <label for="booster-rating-checkbox" class="twp-checkbox-label"></label>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                    <?php /** Nonce Action **/
                    wp_nonce_field('twp_options_nonce', 'twp_options_nonce'); ?>
                    <input type="submit" class="twp-button button-primary" value="<?php esc_html_e('Save Settings','booster-extension') ?>" id="twp_form_submit" name="twp_form_submit"/>
                </form>
            </div>
        </div>
        <div class="twp-content-aside">
            <div class="aside-wrapper-options twp-block-panel">
                <div class="twp-theme-infos">
                    <h2 class="theme-infos-title"><?php esc_html_e('Recommendations','booster-extension'); ?></h2>
                    <div class="twp-premium-themes">
                        <h3><a href="<?php echo esc_url('https://www.themeinwp.com/theme/category/pro/'); ?>"><?php esc_html_e('Premium Themes','booster-extension'); ?></a></h3>
                        <p>
                            <?php esc_html_e('Check out our simple, clean and responsive Premium WordPress Themes that come with an array of crucial features with a superior functionality.','booster-extension'); ?>
                        </p>
                        <p>
                            <a href="<?php echo esc_url('https://www.themeinwp.com/theme/category/pro/'); ?>"><?php esc_html_e('Browse our premium themes.','booster-extension'); ?></a>
                        </p>
                    </div>
                    <div class="twp-free-themes">
                        <h3><a href="<?php echo esc_url('https://www.themeinwp.com/theme/category/free/'); ?>"><?php esc_html_e('Free Themes','booster-extension'); ?></a></h3>
                        <p>
                            <?php esc_html_e('Check out our collection of Free WordPress Themes that are clean, simple and feature-rich.','booster-extension'); ?>
                        </p>
                        <p>
                            <a href="<?php echo esc_url('https://www.themeinwp.com/theme/category/free/'); ?>"><?php esc_html_e('Browse our free themes.','booster-extension'); ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="aside-wrapper-options aside-wrapper-options-2 twp-block-panel">
                <div>
                    <h2><?php esc_html_e('Looking for help?','booster-extension'); ?></h2>
                    <p><?php esc_html_e('We have some resources available to help you in the right direction.','booster-extension'); ?></p>
                    <ul>
                        <li>
                            <a href="<?php echo esc_url('https://www.themeinwp.com/support/'); ?>"><?php esc_html_e('Create Support Ticket','booster-extension'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url('https://www.themeinwp.com/knowledgebase_category/booster-extension/'); ?>"><?php esc_html_e('Knowledge Base','booster-extension'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url('https://www.themeinwp.com/booster-extension/'); ?>"><?php esc_html_e('Frequently Asked Questions','booster-extension'); ?></a>
                        </li>
                    </ul>
                    <p><?php esc_html_e('If your answer can not be found in the resources listed above, please use the support forums on WordPress.org.','booster-extension'); ?></p>
                    <p><?php esc_html_e('Found a bug? Please open an issue on','booster-extension'); ?><a href="<?php echo esc_url(''); ?>"><?php esc_html_e('Help Page','booster-extension'); ?></a></p>
                </div>
            </div>
        </div>
    </div>
</div>