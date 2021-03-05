<?php
/**
 * Woocommerce Compatibility.
 *
 * @link https://woocommerce.com/
 *
 * @package Mate
 */

if ( class_exists('WooCommerce') ) {

    remove_action( 'woocommerce_sidebar','woocommerce_get_sidebar',10 );

}

if( !function_exists('mate_woocommerc_widgets_init') ){

    /**
    * Woocommerce Widget Area.
    */
    function mate_woocommerc_widgets_init(){

        register_sidebar( array(
            'name' => esc_html__('WooCommerce Sidebar', 'mate'),
            'id' => 'mate-woocommerce-widget',
            'description' => esc_html__('Add widgets here.', 'mate'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));

    }

}

if( class_exists('WooCommerce') ){

    add_action('widgets_init', 'mate_woocommerc_widgets_init');

}

if( !function_exists('mate_woocommerce_setup') ):

    /**
     * Woocommerce support.
     */
    function mate_woocommerce_setup(){

        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('woocommerce', array(
            'gallery_thumbnail_image_width' => 300,
        ));

    }

endif;

add_action('after_setup_theme', 'mate_woocommerce_setup');

if( !function_exists('mate_woocommerce_before_main_content') ):

    // Before Main Content woocommerce hook
    function mate_woocommerce_before_main_content(){

        echo '<div class="theme-section theme-single-section">';
        echo '<div class="wrapper">';
        echo '<div class="wrapper-inner">';

    }

endif;

if( class_exists('WooCommerce') ){

    add_action('woocommerce_before_main_content', 'mate_woocommerce_before_main_content', 5);

}

if( !function_exists('mate_woocommerce_after_main_content') ):

    // After Main Content woocommerce hook
    function mate_woocommerce_after_main_content(){

    	$default = mate_get_default_theme_options();
        $sidebar_layout = esc_html(get_theme_mod('global_sidebar_layout', ''));

        if ($sidebar_layout != 'no-sidebar') {
            if (!is_active_sidebar('mate-woocommerce-widget')) {
                return;
            } ?>
            <aside id="secondary" class="widget-area">
                <?php dynamic_sidebar('mate-woocommerce-widget'); ?>
            </aside><!-- #secondary -->
        <?php }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

endif;

if( class_exists('WooCommerce') ){

    add_action('woocommerce_after_main_content', 'mate_woocommerce_after_main_content', 15);

}