<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Mate
 * @since 1.0.0
 */

$mate_default = mate_get_default_theme_options();
$mate_post_layout = esc_html(get_post_meta(get_the_ID(), 'mate_post_layout', true));
$mate_ed_feature_image = esc_html(get_post_meta(get_the_ID(), 'mate_ed_feature_image', true));

if (is_page()) {

    $mate_post_layout = 'layout-1';

}
if ($mate_post_layout == '' || $mate_post_layout == 'global-layout') {

    $mate_post_layout = get_theme_mod('mate_single_post_layout', $mate_default['mate_single_post_layout']);

}

$mate_ed_post_views = esc_html( get_post_meta( get_the_ID(), 'mate_ed_post_views', true ) );
$mate_ed_post_read_time = esc_html( get_post_meta( get_the_ID(), 'mate_ed_post_read_time', true ) );
$mate_ed_post_like_dislike = esc_html( get_post_meta( get_the_ID(), 'mate_ed_post_like_dislike', true ) );
$mate_ed_post_author_box = esc_html( get_post_meta( get_the_ID(), 'mate_ed_post_author_box', true ) );
$mate_ed_post_social_share = esc_html( get_post_meta( get_the_ID(), 'mate_ed_post_social_share', true ) );
$mate_ed_post_reaction = esc_html( get_post_meta( get_the_ID(), 'mate_ed_post_reaction', true ) );

if( $mate_ed_post_views ){

    mate_disable_post_views();

}
if( $mate_ed_post_read_time ){

    mate_disable_post_read_time();

}
if( $mate_ed_post_like_dislike ){

    mate_disable_post_like_dislike();

}
if( $mate_ed_post_author_box ){

    mate_disable_post_author_box();

}
if( $mate_ed_post_reaction ){

    mate_disable_post_reaction();

}

$twp_gradientcolor_type = esc_html( get_post_meta( get_the_ID(), 'twp_gradientcolor_type', true ) );

if( empty( $twp_gradientcolor_type ) || $twp_gradientcolor_type == 'global' ){
    $color_type = get_theme_mod('global_single_gradient_overlay_color', $mate_default['global_single_gradient_overlay_color']);
}else{
    $color_type = $twp_gradientcolor_type;
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if( has_post_thumbnail() ){

        if( is_single() ){

            if( empty( $mate_ed_feature_image ) && $mate_post_layout != 'layout-2' ){
                ?>
                <div class="article-panel panel-scheme-<?php echo esc_attr( $color_type ); ?>">
                    <div class="entry-thumbnail">
                        <?php
                        $image_size = mate_image_size();
                        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),$image_size ); ?>
                        <img src="<?php echo esc_url( $featured_image[0] ); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>">
                        <div class="background-tint"></div>
                        <div class="background-gradient"></div>
                    </div>
                </div><?php
            }

        }else{ ?>

            <div class="entry-thumbnail">

                <?php
                $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' ); ?>
                <img src="<?php echo esc_url( $featured_image[0] ); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>">

            </div>

            <?php
        }
    }

    if( is_singular() && $mate_post_layout != 'layout-2' ){ ?>

        <header class="entry-header">

            <?php if( $mate_post_layout != 'layout-2' && is_single() && 'post' === get_post_type() ){ ?>

                <div class="entry-meta">
                    <?php mate_entry_footer( $cats = true, $tags = false, $edits = false ); ?>
                </div>

            <?php } ?>

            <h1 class="entry-title entry-title-large">

                <?php the_title(); ?>

            </h1>

            <?php
            if( $mate_post_layout != 'layout-2' && is_single() && 'post' === get_post_type() ){

                ?>
                <div class="entry-meta-bottom">
                    <div class="entry-meta">
                        <?php
                        mate_posted_on();
                        mate_posted_by();
                        ?>
                    </div>
                </div>
            <?php } ?>

        </header>

    <?php }

    ?>

    <div class="entry-details-wrapper">

        <?php if( is_singular() && empty( $mate_ed_post_social_share ) && class_exists( 'Booster_Extension_Class' ) && 'post' === get_post_type() ){ ?>

            <div class="entry-details-share">
                <?php echo do_shortcode( '[booster-extension-ss layout="layout-1" status="enable"]' ); ?>
            </div>

        <?php } ?>

        <div class="entry-details">

            <div class="entry-content">

                <?php

                the_content( sprintf(
                /* translators: %s: Name of current post. */
                    wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'mate' ), array( 'span' => array('class' => array() ) ) ),
                    the_title( '<span class="screen-reader-text">"', '"</span>', false )
                ) );

                if (!class_exists('Booster_Extension_Class')) {

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'mate'),
                        'after' => '</div>',
                    ));

                } ?>

            </div>

            <?php
            if( is_singular() && 'post' === get_post_type() ){ ?>

                <div class="entry-footer">
                    <div class="entry-meta">
                        <?php mate_entry_footer($cats = false, $tags = true, $edits = true); ?>
                    </div>
                </div>

            <?php } ?>

        </div>

    </div>

</article>