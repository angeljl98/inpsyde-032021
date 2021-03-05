<?php
/**
 * Header file for the Mate WordPress theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Mate
 * @since 1.0.0
 */
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
if( function_exists('wp_body_open') ){
    wp_body_open();
}

$mate_default = mate_get_default_theme_options();
$ed_preloader = get_theme_mod( 'ed_preloader',$mate_default['ed_preloader'] );

if( $ed_preloader ){ ?>

	<div class="preloader hide-no-js <?php if( isset( $_COOKIE['ThemeNightMode'] ) && $_COOKIE['ThemeNightMode'] == 'true' ){ echo 'preloader-night-mode'; } ?>">
	    <div class="preloader-wrapper">
	        <div class="loader">
	            <span></span><span></span><span></span><span></span><span></span>
	        </div>
	    </div>
	</div>

<?php } ?>


<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e('Skip to the content', 'mate'); ?></a>

<?php get_template_part( 'template-parts/header/header', 'content' ); ?>

	<?php
	if( is_home() && ( get_header_video_url() || has_header_image() ) ){

		$ed_header_image = get_theme_mod( 'ed_header_image',$mate_default['ed_header_image'] );

		if( $ed_header_image ){
            $header_text = get_theme_mod( 'header_text',$mate_default['header_text'] );
            $header_button_label = get_theme_mod( 'header_button_label',$mate_default['header_button_label'] );
            $header_button_link = get_theme_mod( 'header_button_link' );
            $header_description = get_theme_mod( 'header_description' );
            ?>

			<section class="theme-section custom-header-section custom-header">
				
				<div class="custom-header-media">
					<?php the_custom_header_markup(); ?>
				</div>

                <?php
                if( $header_text || $header_button_link ){ ?>

                    <div class="header-media-content">
                        <div class="wrapper">
                            <div class="wrapper-inner">
                                <div class="column column-12">
                                    <div class="theme-section-heading">

                                        <?php if( $header_text ){ ?>

                                            <h2 class="theme-section-title"><?php echo esc_html( $header_text ); ?></h2>
                                        
                                        <?php } ?>

                                        <?php if( $header_description ){ ?>

                                            <p class="theme-section-description">
                                                <?php echo esc_html( $header_description ); ?>
                                            </p>

                                        <?php } ?>

                                        <?php if( $header_button_label ){ ?>

                                            <a href="<?php echo esc_url( $header_button_link ); ?>" class="button button-filled">
                                                <?php echo esc_html( $header_button_label ); ?>
                                            </a>

                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

			</section>

		<?php 
		}

	} ?>

<div id="content" class="site-content">
	
    <?php mate_header_banner_single(); ?>
    <?php mate_header_banner_archive(); ?>