<?php
/**
 * Mate Theme Customizer
 *
 * @package Mate
 */

/** Sanitize Functions. **/
	require get_template_directory() . '/inc/customizer/default.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if (!function_exists('mate_customize_register')) :

function mate_customize_register( $wp_customize ) {

	require get_template_directory() . '/inc/customizer/custom-classes.php';
	require get_template_directory() . '/inc/customizer/sanitize.php';
	require get_template_directory() . '/inc/customizer/layout.php';
	require get_template_directory() . '/inc/customizer/preloader.php';
	require get_template_directory() . '/inc/customizer/header.php';
	require get_template_directory() . '/inc/customizer/pagination.php';
	require get_template_directory() . '/inc/customizer/post.php';
	require get_template_directory() . '/inc/customizer/single.php';
	require get_template_directory() . '/inc/customizer/footer.php';
	require get_template_directory() . '/inc/customizer/home-content.php';
	require get_template_directory() . '/inc/customizer/color-scheme.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_section( 'colors' )->panel = 'theme_colors_panel';
	$wp_customize->get_section( 'colors' )->title = esc_html__('Color Options','mate');
	$wp_customize->get_section( 'title_tagline' )->panel = 'theme_general_settings';
	$wp_customize->get_section( 'header_image' )->panel = 'theme_general_settings';
	$wp_customize->get_section( 'background_image' )->panel = 'theme_general_settings';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'mate_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'mate_customize_partial_blogdescription',
		) );
	}

	// Theme Options Panel.
	$wp_customize->add_panel( 'theme_option_panel',
		array(
			'title'      => esc_html__( 'Theme Options', 'mate' ),
			'priority'   => 150,
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_panel( 'theme_general_settings',
		array(
			'title'      => esc_html__( 'General Settings', 'mate' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_panel( 'theme_colors_panel',
		array(
			'title'      => esc_html__( 'Color Settings', 'mate' ),
			'priority'   => 15,
			'capability' => 'edit_theme_options',
		)
	);

	// Theme Options Panel.
	$wp_customize->add_panel( 'theme_footer_option_panel',
		array(
			'title'      => esc_html__( 'Footer Setting', 'mate' ),
			'priority'   => 150,
			'capability' => 'edit_theme_options',
		)
	);

	// Template Options
	$wp_customize->add_panel( 'theme_template_pannel',
		array(
			'title'      => esc_html__( 'Template Settings', 'mate' ),
			'priority'   => 150,
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_setting('ed_header_image',
	    array(
	        'default' => $mate_default['ed_header_image'],
	        'capability' => 'edit_theme_options',
	        'sanitize_callback' => 'mate_sanitize_checkbox',
	    )
	);
	$wp_customize->add_control('ed_header_image',
	    array(
	        'label' => esc_html__('Enable Header Image', 'mate'),
	        'section' => 'header_image',
	        'type' => 'checkbox',
	        'priority'  => 1,
	    )
	);


	$wp_customize->add_setting( 'header_text',
		array(
		'default'           => $mate_default['header_text'],
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'header_text',
		array(
		'label'    => esc_html__( 'Header Text', 'mate' ),
		'section'  => 'header_image',
		'type'     => 'text',
		)
	);

	$wp_customize->add_setting( 'header_description',
		array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'header_description',
		array(
		'label'    => esc_html__( 'Header Description', 'mate' ),
		'section'  => 'header_image',
		'type'     => 'textarea',
		)
	);

	$wp_customize->add_setting( 'header_button_label',
		array(
		'default'           => $mate_default['header_button_label'],
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'header_button_label',
		array(
		'label'    => esc_html__( 'Header Button label', 'mate' ),
		'section'  => 'header_image',
		'type'     => 'text',
		)
	);

	$wp_customize->add_setting( 'header_button_link',
		array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control( 'header_button_link',
		array(
		'label'    => esc_html__( 'Header Button Link', 'mate' ),
		'section'  => 'header_image',
		'type'     => 'text',
		)
	);

	// Register custom section types.
	$wp_customize->register_section_type( 'Mate_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new Mate_Customize_Section_Upsell(
			$wp_customize,
			'theme_upsell',
			array(
				'title'    => esc_html__( 'Mate Pro', 'mate' ),
				'pro_text' => esc_html__( 'Upgrade To Pro', 'mate' ),
				'pro_url'  => esc_url('https://www.themeinwp.com/theme/mate-pro/'),
				'priority'  => 1,
			)
		)
	);

}

endif;
add_action( 'customize_register', 'mate_customize_register' );

/**
 * Customizer Enqueue scripts and styles.
 */

if (!function_exists('mate_customizer_scripts')) :

    function mate_customizer_scripts(){   
    	
    	wp_enqueue_script('jquery-ui-button');
    	wp_enqueue_style('mate-customizer', get_template_directory_uri() . '/assets/lib/custom/css/customizer.css');
	    wp_enqueue_style('sifter', get_template_directory_uri() . '/assets/lib/custom/css/sifter.css');
	    wp_enqueue_style('mate-repeater', get_template_directory_uri() . '/assets/lib/custom/css/repeater.css');

	    wp_enqueue_script('sifter', get_template_directory_uri() . '/assets/lib/custom/js/sifter.js', array('jquery','customize-controls'), '', 1);
	    wp_enqueue_script('mate-repeater', get_template_directory_uri() . '/assets/lib/custom/js/repeater.js', array('jquery','customize-controls'), '', 1);
	    wp_enqueue_script('mate-customizer', get_template_directory_uri() . '/assets/lib/custom/js/customizer.js', array('jquery','customize-controls'), '', 1);

	    wp_localize_script( 
	        'mate-repeater', 
	        'mate_repeater',
	        array(
	            'optionns'   =>  "<option value='banner'>". esc_html__('Banner Section','mate')."</option>
	            <option value='grid'>". esc_html__('Grid Section','mate')."</option>
	            <option value='carousel'>". esc_html__('Carousel Section','mate')."</option>
	            <option value='mix-grid'>". esc_html__('Mix Grid Section','mate')."</option>
	            <option value='you-may-also-like'>". esc_html__('You May Also Like Section','mate')."</option>
	            <option value='latest'>". esc_html__('Latest Posts Section','mate')."</option>
	            <option value='category'>". esc_html__('Category Section','mate')."</option>",
	             'new_section'   =>  esc_html__('New Section','mate'),
	             'uplolead_image'   =>  esc_html__('Choose Image','mate'),
	             'use_imahe'   =>  esc_html__('Select','mate'),
	         )
	    );

	    $ajax_nonce = wp_create_nonce('mate_ajax_nonce');
        wp_localize_script( 
		    'mate-customizer', 
		    'mate_customizer',
		    array(
		        'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
		        'ajax_nonce' => $ajax_nonce,
		     )
		);
    }

endif;

add_action('customize_controls_enqueue_scripts', 'mate_customizer_scripts');
add_action('customize_controls_init', 'mate_customizer_scripts');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */

if (!function_exists('mate_customize_partial_blogname')) :

	function mate_customize_partial_blogname() {
		bloginfo( 'name' );
	}
endif;

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */

if (!function_exists('mate_customize_partial_blogdescription')) :

	function mate_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}

endif;


add_action('wp_ajax_mate_customizer_font_weight', 'mate_customizer_font_weight_callback');
add_action('wp_ajax_nopriv_mate_customizer_font_weight', 'mate_customizer_font_weight_callback');

// Recommendec Post Ajax Call Function.
function mate_customizer_font_weight_callback() {

    if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce(  sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'mate_ajax_nonce' ) && isset( $_POST['currentfont'] ) && sanitize_text_field( wp_unslash( $_POST['currentfont'] ) ) ) {

       $currentfont = sanitize_text_field( wp_unslash( $_POST['currentfont'] ) );
       $headings_fonts_property = Mate_Fonts::mate_get_fonts_property( $currentfont );

       foreach( $headings_fonts_property['weight'] as $key => $value ){
       		echo '<option value="'.esc_attr( $key ).'">'.esc_html( $value ).'</option>';
       }
    }
    wp_die();
}