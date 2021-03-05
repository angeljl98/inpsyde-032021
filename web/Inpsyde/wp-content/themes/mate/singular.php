<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Mate
 * @since 1.0.0
 */
get_header();

    $mate_default = mate_get_default_theme_options();
    $primary_class = mate_get_sidebar_primary_class();
    $twp_navigation_type = esc_attr( get_post_meta( get_the_ID(), 'twp_disable_ajax_load_next_post', true ) );
    $current_id = '';
    global $post;
    $current_id = $post->ID;
    $single_layout_class = ' single-layout-default';

    if( $twp_navigation_type == '' || $twp_navigation_type == 'global-layout' ){
        $twp_navigation_type = get_theme_mod('twp_navigation_type', $mate_default['twp_navigation_type']);
    }

    $mate_post_layout = esc_html( get_post_meta( $post->ID, 'mate_post_layout', true ) );
    if( $mate_post_layout == '' || $mate_post_layout == 'global-layout' ){
        
        $mate_post_layout = get_theme_mod( 'mate_single_post_layout',$mate_default['mate_single_post_layout'] );
    }
    if( $mate_post_layout == 'layout-2' ){
        $single_layout_class = ' single-layout-banner';
    }
 
    $mate_ed_post_rating = esc_html( get_post_meta( $post->ID, 'mate_ed_post_rating', true ) ); ?>

    <section class="theme-section theme-single-section">
        <div class="wrapper">
            <div class="wrapper-inner">

                <div id="primary" class="content-area <?php echo esc_attr( $primary_class ); ?>">
                    <main id="site-content" class="<?php if( $mate_ed_post_rating ){ echo 'mate-no-comment'; } ?>" role="main">

                        <?php

                        if( have_posts() ): ?>

                            <div class="article-wraper <?php echo esc_attr($single_layout_class); ?>">

                                <?php while (have_posts()) :
                                    the_post();

                                    get_template_part('template-parts/content', 'single');

                                    /**
                                     *  Output comments wrapper if it's a post, or if comments are open,
                                     * or if there's a comment number – and check for password.
                                    **/

                                    if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && !post_password_required() ) { ?>

                                        <div class="comments-wrapper">
                                            <?php comments_template(); ?>
                                        </div><!-- .comments-wrapper -->

                                    <?php
                                    }

                                endwhile; ?>

                            </div>

                        <?php
                        else :

                            get_template_part('template-parts/content', 'none');

                        endif;

                        /**
                         * Navigation
                         * 
                         * @hooked mate_post_floating_nav - 10  
                         * @hooked mate_related_posts - 20  
                         * @hooked mate_single_post_navigation - 30  
                        */

                        do_action('mate_navigation_action'); ?>

                    </main><!-- #main -->
                </div>

                <?php mate_get_sidebar(); ?>

            </div>
        </div>
    </section>

<?php
get_footer();
