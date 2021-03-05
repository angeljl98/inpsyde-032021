<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Author Widgets.
 *
 * @package Booster Extension
 */
if (!function_exists('booster_extionsion_bp_author_widgets')) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function booster_extionsion_bp_author_widgets()
    {
        // Auther widget.
        register_widget('Booster_Extension_Author_widget');
    }
endif;
add_action('widgets_init', 'booster_extionsion_bp_author_widgets');
/*Video widget*/
if (!class_exists('Booster_Extension_Author_widget')) :
    /**
     * Author widget Class.
     *
     * @since 1.0.0
     */
    class Booster_Extension_Author_widget extends Booster_Extension_Widget_Base
    {
        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $opts = array(
                'classname' => 'twp_bp_author_widget',
                'description' => esc_html__('Displays authors details in post.', 'booster-extension'),
                'customize_selective_refresh' => true,
            );
            $fields = array(
                'title' => array(
                    'label' => esc_html__('Title:', 'booster-extension'),
                    'type' => 'text',
                    'class' => 'widefat',
                    'default' => esc_html__('About Author', 'booster-extension'),
                ),
                'widget_author_type' => array(
                    'label' => esc_html__('Author Type:', 'booster-extension'),
                    'type' => 'select',
                    'default' => 'specific-author',
                    'options' => array(
                        'specific-author' => esc_html__('Specific Author', 'booster-extension'),
                        'post-author' => esc_html__('Post Author:', 'booster-extension'),
                    ),
                ),
                'user_ID' => array(
                    'label' => esc_html__('User ID:', 'booster-extension'),
                    'type' => 'number',
                    'default' => 1,
                ),
                'profile_layout' => array(
                    'label' => esc_html__('Profile Image layout:', 'booster-extension'),
                    'type' => 'select',
                    'default' => 'left',
                    'options' => array(
                        'square' => esc_html__('Square', 'booster-extension'),
                        'round' => esc_html__('Round', 'booster-extension'),
                    ),
                ),
                'ed_author_desc' => array(
                    'label' => esc_html__('Show Author Description:', 'booster-extension'),
                    'type' => 'checkbox',
                    'default' => 1,
                ),
                'ed_author_email' => array(
                    'label' => esc_html__('Show Author Email:', 'booster-extension'),
                    'type' => 'checkbox',
                    'default' => 1,
                ),
                'ed_author_url' => array(
                    'label' => esc_html__('Show Author URL:', 'booster-extension'),
                    'type' => 'checkbox',
                    'default' => 1,
                ),
                'ed_author_role' => array(
                    'label' => esc_html__('Show Author Role:', 'booster-extension'),
                    'type' => 'checkbox',
                    'default' => 0,
                ),
            );
            parent::__construct('booster-extension-author-layout', esc_html__('BE: Author Widget', 'booster-extension'), $opts, array(), $fields);
        }
        /**
         * Outputs the content for the current widget instance.
         *
         * @since 1.0.0
         *
         * @param array $args Display arguments.
         * @param array $instance Settings for the current widget instance.
         */
        function widget($args, $instance)
        {
            $params = $this->get_params($instance);
            $widget_author_type = esc_html($params['widget_author_type']);
            $profile_layout = esc_html($params['profile_layout']);
            $ed_author_email = absint($params['ed_author_email']);
            $ed_author_desc = absint($params['ed_author_desc']);
            $ed_author_url = absint($params['ed_author_url']);
            $ed_author_role = absint($params['ed_author_role']);
            echo $args['before_widget'];
            if ($widget_author_type == 'post-author') {
                if (is_single()) {
                    $user_ID = absint(get_the_author_meta('ID'));
                } else {
                    $user_ID = absint($params['user_ID']);
                }
            } else {
                $user_ID = absint($params['user_ID']);
            }
            $user_data = get_userdata($user_ID);
            $user_role = $user_data->roles[0];
            $author_img = get_avatar($user_ID, 400, '', '', array('class' => 'avatar-img'));
            $author_name = esc_html(get_the_author_meta('display_name', $user_ID));
            $author_user_url = esc_url(get_the_author_meta('user_url', $user_ID));
            $author_description = esc_html(get_the_author_meta('description', $user_ID));
            $author_email = esc_html(get_the_author_meta('user_email', $user_ID));
            $twp_user_metabox_facebook = get_the_author_meta('twp_user_metabox_facebook', $user_ID);
            $twp_user_metabox_twitter = get_the_author_meta('twp_user_metabox_twitter', $user_ID);
            $twp_user_metabox_instagram = get_the_author_meta('twp_user_metabox_instagram', $user_ID);
            $twp_user_metabox_pinterest = get_the_author_meta('twp_user_metabox_pinterest', $user_ID);
            $twp_user_metabox_linkedin = get_the_author_meta('twp_user_metabox_linkedin', $user_ID);
            $twp_user_metabox_youtube = get_the_author_meta('twp_user_metabox_youtube', $user_ID);
            $twp_user_metabox_vimeo = get_the_author_meta('twp_user_metabox_vimeo', $user_ID);
            $twp_user_metabox_whatsapp = get_the_author_meta('twp_user_metabox_whatsapp', $user_ID);
            $twp_user_metabox_github = get_the_author_meta('twp_user_metabox_github', $user_ID);
            $twp_user_metabox_wordpress = get_the_author_meta('twp_user_metabox_wordpress', $user_ID);
            $twp_user_metabox_foursquare = get_the_author_meta('twp_user_metabox_foursquare', $user_ID);
            $twp_user_metabox_vk = get_the_author_meta('twp_user_metabox_vk', $user_ID);
            $twp_user_metabox_twitch = get_the_author_meta('twp_user_metabox_twitch', $user_ID);
            $twp_user_metabox_tumblr = get_the_author_meta('twp_user_metabox_tumblr', $user_ID);
            $twp_user_metabox_snapchat = get_the_author_meta('twp_user_metabox_snapchat', $user_ID);
            $twp_user_metabox_skype = get_the_author_meta('twp_user_metabox_skype', $user_ID);
            $twp_user_metabox_reddit = get_the_author_meta('twp_user_metabox_reddit', $user_ID);
            $twp_user_metabox_stackoverflow = get_the_author_meta('twp_user_metabox_stackoverflow', $user_ID);
            $twp_user_metabox_xing = get_the_author_meta('twp_user_metabox_xing', $user_ID);
            $twp_user_metabox_delicious = get_the_author_meta('twp_user_metabox_delicious', $user_ID);
            $twp_user_metabox_soundcloud = get_the_author_meta('twp_user_metabox_soundcloud', $user_ID);
            $twp_user_metabox_behance = get_the_author_meta('twp_user_metabox_behance', $user_ID);
            $twp_user_metabox_steam = get_the_author_meta('twp_user_metabox_steam', $user_ID);
            $twp_user_metabox_dribbble = get_the_author_meta('twp_user_metabox_dribbble', $user_ID);
            $twp_user_metabox_blogger = get_the_author_meta('twp_user_metabox_blogger', $user_ID);
            $twp_user_metabox_flickr = get_the_author_meta('twp_user_metabox_flickr', $user_ID);
            $twp_user_metabox_spotify = get_the_author_meta('twp_user_metabox_spotify', $user_ID);
            $twp_user_metabox_rss = get_the_author_meta('twp_user_metabox_rss', $user_ID);
            if (!empty($params['title'])) {
                echo $args['before_title'] . $params['title'] . $args['after_title'];
            }
            $be_user_avatar = get_the_author_meta( 'be_user_avatar', $user_ID );
            $be_user_background_avatar = get_the_author_meta( 'be_user_background_avatar', $user_ID );

            $bg_class = '';
            if( $be_user_background_avatar ){
                $bg_class = 'author-bg-enable';
            }

            ?>

            <div class="booster-block booster-author-block">
                <div class="be-author-details <?php if (!empty($profile_layout)) {
                    echo esc_attr('layout-' . $profile_layout);
                } ?>">
                    <div class="be-author-wrapper <?php echo $bg_class; ?>">
                        
                        <?php if ( ! empty( $be_user_background_avatar ) ) { ?>
                            <div class="be-author-background booster-bg-image">
                                <img src="<?php echo esc_url( $be_user_background_avatar ); ?>">
                            </div>
                        <?php } ?>
                        
                        <div class="be-author-image booster-bg-image">
                            
                            <?php if( $be_user_avatar ){ ?>
                                
                                <img src="<?php echo esc_url( $be_user_avatar ); ?>">
                            
                            <?php
                            }else{
                                echo wp_kses_post($author_img);
                            } ?>

                        </div>

                        <?php if ( $ed_author_desc && $author_description) { ?>
                            <div class="be-author-meta be-author-description">
                                <?php echo esc_html($author_description); ?>
                            </div>
                        <?php } ?>

                        <?php if ($author_email && $ed_author_email) { ?>

                            <div class="be-author-meta be-author-email">
                                <a href="mailto: <?php echo esc_html($author_email); ?>">
                                    <i class="booster-icon twp-mail-envelope"></i><?php echo esc_html($author_email); ?>
                                </a>
                            </div>

                        <?php } ?>

                        <?php if ($author_user_url && $ed_author_url) { ?>
                            <div class="be-author-meta be-author-url">
                                <a href="<?php echo esc_url($author_user_url); ?>" target="_blank">
                                    <i class="booster-icon twp-sphere"></i><?php echo esc_url($author_user_url); ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ($user_role && $ed_author_role) { ?>
                            <div class="be-author-meta be-author-role">
                                <i class="booster-icon twp-person"></i><?php echo esc_html($user_role); ?>
                            </div>
                        <?php } ?>

                        <div class="be-author-profiles">

                            <?php if ($twp_user_metabox_facebook) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_facebook); ?>">
                                    <i class="booster-icon twp-facebook"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_twitter) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_twitter); ?>">
                                    <i class="booster-icon twp-twitter"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_instagram) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_instagram); ?>">
                                    <i class="booster-icon twp-instagram"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_pinterest) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_pinterest); ?>">
                                    <i class="booster-icon twp-pinterest"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_linkedin) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_linkedin); ?>">
                                    <i class="booster-icon twp-linkedin"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_youtube) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_youtube); ?>">
                                    <i class="booster-icon twp-youtube"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_vimeo) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_vimeo); ?>">
                                    <i class="booster-icon twp-vimeo"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_whatsapp) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_whatsapp); ?>">
                                    <i class="booster-icon twp-whatsapp"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_github) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_github); ?>">
                                    <i class="booster-icon twp-github"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_wordpress) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_wordpress); ?>">
                                    <i class="booster-icon twp-wordpress"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_foursquare) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_foursquare); ?>">
                                    <i class="booster-icon twp-foursquare"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_vk) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_vk); ?>">
                                    <i class="booster-icon twp-vk"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_twitch) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_twitch); ?>">
                                    <i class="booster-icon twp-twitch"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_tumblr) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_tumblr); ?>">
                                    <i class="booster-icon twp-tumblr"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_snapchat) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_snapchat); ?>">
                                    <i class="booster-icon twp-snapchat"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_skype) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_skype); ?>">
                                    <i class="booster-icon twp-skype"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_reddit) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_reddit); ?>">
                                    <i class="booster-icon twp-reddit"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_stackoverflow) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_stackoverflow); ?>">
                                    <i class="booster-icon twp-stackoverflow"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_xing) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_xing); ?>">
                                    <i class="booster-icon twp-xing"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_delicious) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_delicious); ?>">
                                    <i class="booster-icon twp-delicious"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_soundcloud) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_soundcloud); ?>">
                                    <i class="booster-icon twp-soundcloud"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_behance) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_behance); ?>">
                                    <i class="booster-icon twp-behance"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_steam) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_steam); ?>">
                                    <i class="booster-icon twp-steam"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_dribbble) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_dribbble); ?>">
                                    <i class="booster-icon twp-dribbble"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_blogger) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_blogger); ?>">
                                    <i class="booster-icon twp-blogger"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_flickr) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_flickr); ?>">
                                    <i class="booster-icon twp-flickr"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_spotify) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_spotify); ?>">
                                    <i class="booster-icon twp-spotify"></i>
                                </a>
                            <?php } ?>

                            <?php if ($twp_user_metabox_rss) { ?>
                                <a target="_blank" href="<?php echo esc_url($twp_user_metabox_rss); ?>">
                                    <i class="booster-icon twp-rss"></i>
                                </a>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $args['after_widget'];
        }
    }
endif;