<?php
/**
 * Pagination Settings
 *
 * @package Mate
 */

$mate_default = mate_get_default_theme_options();

// Pagination Section.
$wp_customize->add_section( 'mate_pagination_section',
	array(
	'title'      => esc_html__( 'Pagination Settings', 'mate' ),
	'priority'   => 20,
	'capability' => 'edit_theme_options',
	'panel'		 => 'theme_option_panel',
	)
);

// Pagination Layout Settings
$wp_customize->add_setting( 'mate_pagination_layout',
	array(
	'default'           => $mate_default['mate_pagination_layout'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'mate_pagination_layout',
	array(
	'label'       => esc_html__( 'Pagination Method', 'mate' ),
	'section'     => 'mate_pagination_section',
	'type'        => 'select',
	'choices'     => array(
		'next-prev' => esc_html__('Next/Previous Method','mate'),
		'numeric' => esc_html__('Numeric Method','mate'),
		'load-more' => esc_html__('Ajax Load More Button','mate'),
		'auto-load' => esc_html__('Ajax Auto Load','mate'),
	),
	)
);