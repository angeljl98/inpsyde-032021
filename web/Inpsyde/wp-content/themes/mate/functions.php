<?php
/**
 * Mate functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mate
 */


if ( ! function_exists( 'mate_after_theme_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */

	function mate_after_theme_support() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Custom background color.
		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'ffffff',
			)
		);

		// This variable is intended to be overruled from themes.
		// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$GLOBALS['content_width'] = apply_filters( 'mate_content_width', 1280 );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 220,
				'width'       => 90,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);
		
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Mate, use a find and replace
		 * to change 'mate' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mate', get_template_directory() . '/languages' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

	}

endif;

add_action( 'after_setup_theme', 'mate_after_theme_support' );

/**
 * Register and Enqueue Styles.
 */
function mate_register_styles() {

	$fonts_url = mate_fonts_url();
    if (!empty($fonts_url)) {
        wp_enqueue_style('mate-google-fonts', $fonts_url, array(), null);
    }

	$theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/lib/magnific-popup/magnific-popup.css' );
    wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/lib/slick/css/slick.min.css');
	wp_enqueue_style( 'mate-style', get_stylesheet_uri(), array(), $theme_version );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}	

	wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script( 'masonry' );
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/lib/slick/js/slick.min.js', array('jquery'), '', 1);
    wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/lib/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'theia-sticky-sidebar', get_template_directory_uri() . '/assets/lib/theiaStickySidebar/theia-sticky-sidebar.js', array('jquery'), '', true );
	wp_enqueue_script( 'mate-pagination', get_template_directory_uri() . '/assets/lib/custom/js/pagination.js', array('jquery'), '', 1 );
	wp_enqueue_script( 'mate-custom', get_template_directory_uri() . '/assets/lib/custom/js/custom.js', array('jquery'), '', 1);

    $ajax_nonce = wp_create_nonce('mate_ajax_nonce');

    // Global Query
    if( is_front_page() ){

    	$posts_per_page = absint( get_option('posts_per_page') );
        $current_paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
        $posts_args = array(
            'posts_per_page'        => $posts_per_page,
            'paged'                 => $current_paged,
        );
        $posts_qry = new WP_Query( $posts_args );
        $max = $posts_qry->max_num_pages;

    }else{
        global $wp_query;
        $max = $wp_query->max_num_pages;
        $current_paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
    }

    $mate_default = mate_get_default_theme_options();
    $mate_pagination_layout = get_theme_mod( 'mate_pagination_layout',$mate_default['mate_pagination_layout'] );

    // Pagination Data
    wp_localize_script( 
	    'mate-pagination', 
	    'mate_pagination',
	    array(
	        'paged'  => absint( $current_paged ),
	        'maxpage'   => absint( $max ),
	        'nextLink'   => next_posts( $max, false ),
	        'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
	        'loadmore'   => esc_html__( 'Load More Posts', 'mate' ),
	        'nomore'     => esc_html__( 'No More Posts', 'mate' ),
	        'loading'    => esc_html__( 'Loading...', 'mate' ),
	        'pagination_layout'   => esc_html( $mate_pagination_layout ),
	        'ajax_nonce' => $ajax_nonce,
	        'next_icon' => mate_the_theme_svg('chevron-right',true),
	        'prev_icon' => mate_the_theme_svg('chevron-left',true),
	     )
	);

    global $post;
    $single_post = 0;
    $mate_ed_post_reaction = '';
    if( isset( $post->ID ) && isset( $post->post_type ) && $post->post_type == 'post' ){

    	$mate_ed_post_reaction = esc_html( get_post_meta( $post->ID, 'mate_ed_post_reaction', true ) );
    	$single_post = 1;

    }
    
	wp_localize_script(
	    'mate-custom', 
	    'mate_custom',
	    array(
	    	'single_post'			=> absint( $single_post ),
	        'mate_ed_post_reaction' => esc_html( $mate_ed_post_reaction ),
	     )
	);

}

add_action( 'wp_enqueue_scripts', 'mate_register_styles' );

/**
 * Admin enqueue script
 */
function mate_admin_scripts($hook){

	wp_enqueue_media();
    wp_enqueue_style('mate-admin', get_template_directory_uri() . '/assets/lib/custom/css/admin.css');
    wp_enqueue_script('mate-admin', get_template_directory_uri() . '/assets/lib/custom/js/admin.js', array('jquery'), '', 1);

    $ajax_nonce = wp_create_nonce('mate_ajax_nonce');

    wp_localize_script( 
        'mate-admin', 
        'mate_admin',
        array(
            'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
            'ajax_nonce' => $ajax_nonce,
            'active' => esc_html__('Active','mate'),
	        'deactivate' => esc_html__('Deactivate','mate'),
	        'upload_image'   =>  esc_html__('Choose Image','mate'),
            'use_imahe'   =>  esc_html__('Select','mate'),
         )
    );

}

add_action('admin_enqueue_scripts', 'mate_admin_scripts');

if( !function_exists( 'mate_js_no_js_class' ) ) :

	// js no-js class toggle
	function mate_js_no_js_class() { ?>

		<script>document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );</script>
	
	<?php
	}
	
endif;

add_action( 'wp_head', 'mate_js_no_js_class' );

/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function mate_menus() {

	$locations = array(
		'mate-primary-menu'  => esc_html__( 'Primary Menu', 'mate' ),
		'mate-social-menu'  => esc_html__( 'Social Menu', 'mate' ),
	);

	register_nav_menus( $locations );
}

add_action( 'init', 'mate_menus' );

require get_template_directory() . '/classes/admin-notice.php';
require get_template_directory() . '/classes/plugin-classes.php';
require get_template_directory() . '/assets/lib/tgmpa/recommended-plugins.php';
require get_template_directory() . '/classes/class-svg-icons.php';
require get_template_directory() . '/classes/class-walker-menu.php';
require get_template_directory() . '/inc/customizer/customizer.php';
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/single-related-posts.php';
require get_template_directory() . '/inc/custom-functions.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/classes/body-classes.php';
require get_template_directory() . '/inc/widgets/widgets.php';
require get_template_directory() . '/inc/term-meta.php';
require get_template_directory() . '/inc/metabox.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/assets/lib/custom/css/style.php';
require get_template_directory() . '/woocommerce/woocommerce-functions.php';
require get_template_directory() . '/template-parts/home/latest-posts-content.php';
require get_template_directory() . '/template-parts/home/banner.php';
require get_template_directory() . '/template-parts/home/grid.php';
require get_template_directory() . '/template-parts/home/carousel.php';
require get_template_directory() . '/template-parts/home/mix-grid.php';
require get_template_directory() . '/template-parts/home/recommended.php';
require get_template_directory() . '/template-parts/home/category.php';
require get_template_directory() . '/template-parts/home/latest.php';
require get_template_directory() . '/classes/about.php';
require get_template_directory() . '/inc/mega-menu/megamenu-custom-fields.php';
require get_template_directory() . '/inc/mega-menu/walkernav.php';

function mate_video_controls( $settings ) {
	
	$settings['l10n']['play']  = '<span class="screen-reader-text">' . __( 'Play background video', 'mate' ) . '</span>' . mate_the_theme_svg('play',true);
	$settings['l10n']['pause'] = '<span class="screen-reader-text">' . __( 'Pause background video', 'mate' ) . '</span>' . mate_the_theme_svg('pause',true);
	return $settings;

}

add_filter( 'header_video_settings', 'mate_video_controls' );