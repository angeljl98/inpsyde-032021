<?php
/**
* Preloader Options.
*
* @package Mate
*/

$mate_default = mate_get_default_theme_options();

$wp_customize->add_section( 'mate_per_loader',
	array(
	'title'      => esc_html__( 'Preloader Settings', 'mate' ),
	'priority'   => 10,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

$wp_customize->add_setting('ed_preloader',
    array(
        'default' => $mate_default['ed_preloader'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_preloader',
    array(
        'label' => esc_html__('Enable Preloader', 'mate'),
        'section' => 'mate_per_loader',
        'type' => 'checkbox',
    )
);