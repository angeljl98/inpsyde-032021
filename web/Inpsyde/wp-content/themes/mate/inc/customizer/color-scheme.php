<?php
/**
* Color Settings.
*
* @package Mate
*/

$mate_default = mate_get_default_theme_options();

$wp_customize->add_section( 'color_scheme',
    array(
    'title'      => esc_html__( 'Color Scheme', 'mate' ),
    'priority'   => 60,
    'capability' => 'edit_theme_options',
    'panel'      => 'theme_colors_panel',
    )
);

// Color Scheme.
$wp_customize->add_setting(
    'mate_color_schema',
    array(
        'default' 			=> $mate_default['mate_color_schema'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'mate_sanitize_select'
    )
);
$wp_customize->add_control(
    new Mate_Custom_Radio_Color_Schema( 
        $wp_customize,
        'mate_color_schema',
        array(
            'settings'      => 'mate_color_schema',
            'section'       => 'color_scheme',
            'label'         => esc_html__( 'Color Scheme', 'mate' ),
            'choices'       => array(
                'simple'  => array(
                	'color' => array('#ffffff','#F44336','#FF9800','#000000'),
                	'title' => esc_html__('Simple','mate'),
                ),
                'fancy'  => array(
                	'color' => array('#faf7f2','#017eff','#fc9285','#455d58'),
                	'title' => esc_html__('Fancy','mate'),
                ),
                'dark'  => array(
                	'color' => array('#222222','#007CED','#fb7268','#ffffff'),
                	'title' => esc_html__('Dark','mate'),
                ),
            )
        )
    )
);