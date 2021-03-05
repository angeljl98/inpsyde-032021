<?php
/**
* Layouts Settings.
*
* @package Mate
*/

$mate_default = mate_get_default_theme_options();
$sidebar_layout = mate_sidebar_layout();
$gradient_overlay_color_option = mate_gradient_overlay_color_option();

// Layout Section.
$wp_customize->add_section( 'layout_setting',
	array(
	'title'      => esc_html__( 'Archive Settings', 'mate' ),
	'priority'   => 60,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Global Sidebar Layout.
$wp_customize->add_setting( 'global_sidebar_layout',
	array(
	'default'           => $mate_default['global_sidebar_layout'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'mate_sanitize_sidebar_option',
	)
);
$wp_customize->add_control( 'global_sidebar_layout',
	array(
	'label'       => esc_html__( 'Global Sidebar Layout', 'mate' ),
	'section'     => 'layout_setting',
	'type'        => 'select',
	'choices'     => $sidebar_layout,
	)
);

$wp_customize->add_setting( 'global_archive_gradient_overlay_color',
    array(
    'default'           => $mate_default['global_archive_gradient_overlay_color'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control( 'global_archive_gradient_overlay_color',
    array(
    'label'       => esc_html__( 'Global Archive Gradient Overlay Color', 'mate' ),
    'section'     => 'layout_setting',
    'type'        => 'select',
    'choices'     => $gradient_overlay_color_option,
    )
);