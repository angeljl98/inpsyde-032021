<?php
/**
* Header Options.
*
* @package Mate
*/

$mate_default = mate_get_default_theme_options();
$mate_page_lists = mate_page_lists();

// Header Advertise Area Section.
$wp_customize->add_section( 'main_header_setting',
	array(
	'title'      => esc_html__( 'Header Settings', 'mate' ),
	'priority'   => 10,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

$wp_customize->add_setting('ed_overlap_header',
    array(
        'default' => $mate_default['ed_overlap_header'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_overlap_header',
    array(
        'label' => esc_html__('Enable Transparent Header Area', 'mate'),
        'section' => 'main_header_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('ed_day_night_mode_switch',
    array(
        'default' => $mate_default['ed_day_night_mode_switch'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_day_night_mode_switch',
    array(
        'label' => esc_html__('Enable Dark and Night Mode', 'mate'),
        'section' => 'main_header_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('ed_header_search',
    array(
        'default' => $mate_default['ed_header_search'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_header_search',
    array(
        'label' => esc_html__('Enable Search', 'mate'),
        'section' => 'main_header_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('ed_responsive_menu',
    array(
        'default' => $mate_default['ed_responsive_menu'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_responsive_menu',
    array(
        'label' => esc_html__('Display Hamburger Menu Icon on Desktop View', 'mate'),
        'section' => 'main_header_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('ed_header_search_recent_posts',
    array(
        'default' => $mate_default['ed_header_search_recent_posts'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_header_search_recent_posts',
    array(
        'label' => esc_html__('Enable Recent Posts on Search Area', 'mate'),
        'section' => 'main_header_setting',
        'type' => 'checkbox',
    )
);
$wp_customize->add_setting( 'recent_post_title_search',
    array(
    'default'           => $mate_default['recent_post_title_search'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'recent_post_title_search',
    array(
    'label'    => esc_html__( 'Related Posts Section Title', 'mate' ),
    'section'  => 'main_header_setting',
    'type'     => 'text',
    )
);
$wp_customize->add_setting('ed_header_search_top_category',
    array(
        'default' => $mate_default['ed_header_search_top_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_checkbox',
    )
);
$wp_customize->add_control('ed_header_search_top_category',
    array(
        'label' => esc_html__('Enable Top Category on Search Area', 'mate'),
        'section' => 'main_header_setting',
        'type' => 'checkbox',
    )
);
$wp_customize->add_setting( 'top_category_title_search',
    array(
    'default'           => $mate_default['top_category_title_search'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'top_category_title_search',
    array(
    'label'    => esc_html__( 'Top Category Section Title', 'mate' ),
    'section'  => 'main_header_setting',
    'type'     => 'text',
    )
);