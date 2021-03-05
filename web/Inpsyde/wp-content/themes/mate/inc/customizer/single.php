<?php
/**
* Single Post Options.
*
* @package Mate
*/

$mate_default = mate_get_default_theme_options();
$sidebar_layout = mate_sidebar_layout();
$gradient_overlay_color_option = mate_gradient_overlay_color_option();

$wp_customize->add_section( 'single_post_setting',
	array(
	'title'      => esc_html__( 'Single Post Settings', 'mate' ),
	'priority'   => 35,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

$wp_customize->add_setting('ed_related_post',
    array(
        'default' => $mate_default['ed_related_post'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_related_post',
    array(
        'label' => esc_html__('Enable Related Posts', 'mate'),
        'section' => 'single_post_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting( 'related_post_title',
    array(
    'default'           => $mate_default['related_post_title'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'related_post_title',
    array(
    'label'    => esc_html__( 'Related Posts Section Title', 'mate' ),
    'section'  => 'single_post_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting('twp_navigation_type',
    array(
        'default' => $mate_default['twp_navigation_type'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_single_pagination_layout',
    )
);
$wp_customize->add_control('twp_navigation_type',
    array(
        'label' => esc_html__('Single Post Navigation Type', 'mate'),
        'section' => 'single_post_setting',
        'type' => 'select',
        'choices' => array(
                'no-navigation' => esc_html__('Disable Navigation','mate' ),
                'norma-navigation' => esc_html__('Next Previous Navigation','mate' ),
                'ajax-next-post-load' => esc_html__('Ajax Load Next 3 Posts Contents','mate' )
            ),
    )
);

$wp_customize->add_setting('ed_floating_next_previous_nav',
    array(
        'default' => $mate_default['ed_floating_next_previous_nav'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_floating_next_previous_nav',
    array(
        'label' => esc_html__('Enable Hoverable Sidenav Next/Previous Buttons', 'mate'),
        'section' => 'single_post_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting(
    'mate_single_post_layout',
    array(
        'default'           => $mate_default['mate_single_post_layout'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_single_post_layout'
    )
);
$wp_customize->add_control(
    new Mate_Custom_Radio_Image_Control( 
        $wp_customize,
        'mate_single_post_layout',
        array(
            'settings'      => 'mate_single_post_layout',
            'section'       => 'single_post_setting',
            'label'         => esc_html__( 'Glabal Appearance Layout', 'mate' ),
            'choices'       => array(
                'layout-1'  => get_template_directory_uri() . '/assets/images/Layout-style-1.png',
                'layout-2'  => get_template_directory_uri() . '/assets/images/Layout-style-2.png',
            )
        )
    )
);

$wp_customize->add_setting( 'global_single_sidebar_layout',
    array(
    'default'           => $mate_default['global_single_sidebar_layout'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'mate_sanitize_sidebar_option',
    )
);
$wp_customize->add_control( 'global_single_sidebar_layout',
    array(
    'label'       => esc_html__( 'Global Single Post Sidebar Layout', 'mate' ),
    'section'     => 'single_post_setting',
    'type'        => 'select',
    'choices'     => $sidebar_layout,
    )
);

$wp_customize->add_setting( 'global_single_gradient_overlay_color',
    array(
    'default'           => $mate_default['global_single_gradient_overlay_color'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control( 'global_single_gradient_overlay_color',
    array(
    'label'       => esc_html__( 'Global Single Gradient Overlay Color', 'mate' ),
    'section'     => 'single_post_setting',
    'type'        => 'select',
    'choices'     => $gradient_overlay_color_option,
    )
);