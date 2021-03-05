<?php
/**
* Posts Settings.
*
* @package Mate
*/

$mate_default = mate_get_default_theme_options();

// Single Post Section.
$wp_customize->add_section( 'posts_settings',
	array(
	'title'      => esc_html__( 'Entry Meta Settings', 'mate' ),
	'priority'   => 35,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

$wp_customize->add_setting('ed_post_author',
    array(
        'default' => $mate_default['ed_post_author'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_post_author',
    array(
        'label' => esc_html__('Enable Posts Author', 'mate'),
        'section' => 'posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('ed_post_date',
    array(
        'default' => $mate_default['ed_post_date'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_post_date',
    array(
        'label' => esc_html__('Enable Posts Date', 'mate'),
        'section' => 'posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('ed_post_category',
    array(
        'default' => $mate_default['ed_post_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_post_category',
    array(
        'label' => esc_html__('Enable Posts Category', 'mate'),
        'section' => 'posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('ed_post_tags',
    array(
        'default' => $mate_default['ed_post_tags'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_post_tags',
    array(
        'label' => esc_html__('Enable Posts Tags', 'mate'),
        'section' => 'posts_settings',
        'type' => 'checkbox',
    )
);