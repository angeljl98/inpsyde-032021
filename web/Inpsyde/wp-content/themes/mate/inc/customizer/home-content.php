<?php
/**
* Sections Repeater Options.
*
* @package Mate
*/

$mate_post_category_list = mate_post_category_list();
$mate_page_lists = mate_page_lists();
$mate_default = mate_get_default_theme_options();
$home_sections = mate_sanitize_home_type();
$sidebar_layout = mate_sidebar_layout();
// Slider Section.
$wp_customize->add_section( 'home_sections_repeater',
    array(
        'title'      => esc_html__( 'Homepage Content', 'mate' ),
        'priority'   => 130,
        'capability' => 'edit_theme_options',
    )
);

$column_number = array(
    '2' => esc_html__('2 Column','mate'),
    '3' => esc_html__('3 Column','mate'),
    '4' => esc_html__('4 Column','mate'),
);

$ed_meta_option = array(
    'all-cat' => esc_html__('Show All Category','mate'),
    'current-cat' => esc_html__('Only Show Current Selected Category','mate'),
    'hide-current-cat' => esc_html__('Hide Current Selected Category','mate'),
    'hide-cat' => esc_html__("Don't Show Category",'mate'),
);

// Recommended Posts Enable Disable.
$wp_customize->add_setting( 'twp_mate_home_sections_1', array(
    'sanitize_callback' => 'mate_sanitize_repeater',
    'default' => json_encode( $mate_default['twp_mate_home_sections_1'] ),
));

$wp_customize->add_control(  new Mate_Repeater_Controler( $wp_customize, 'twp_mate_home_sections_1', 
    array(
        'section' => 'home_sections_repeater',
        'settings' => 'twp_mate_home_sections_1',
        'mate_box_label' => esc_html__('New Section','mate'),
        'mate_box_add_control' => esc_html__('Add New Section','mate'),
    ),
    array(

        'section_ed' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Section', 'mate' ),
            'class'       => 'home-section-ed'
        ),
        'home_section_type' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Section Type', 'mate' ),
            'options'     => $home_sections,
            'class'       => 'home-section-type'
        ),
        'section_title' => array(
            'type'        => 'text',
            'label'       => esc_html__( 'Section Title', 'mate' ),
            'class'       => 'home-repeater-fields-hs grid-fields category-fields carousel-fields mix-grid-fields you-may-also-like-fields'
        ),
        'section_sub_title' => array(
            'type'        => 'text',
            'label'       => esc_html__( 'Section Sub Title', 'mate' ),
            'class'       => 'home-repeater-fields-hs grid-fields category-fields carousel-fields mix-grid-fields you-may-also-like-fields'
        ),
        'category_article_title' => array(
            'type'        => 'text',
            'label'       => esc_html__( 'Article Posts Title', 'mate' ),
            'class'       => 'home-repeater-fields-hs category-fields'
        ),
        'section_post_category' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Slider Category', 'mate' ),
            'options'     => $mate_post_category_list,
            'class'       => 'home-repeater-fields-hs banner-fields grid-fields carousel-fields mix-grid-fields you-may-also-like-fields'
        ),
        'section_category_1' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Category One', 'mate' ),
            'options'     => $mate_post_category_list,
            'class'       => 'home-repeater-fields-hs category-fields'
        ),
        'section_category_2' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Category Two', 'mate' ),
            'options'     => $mate_post_category_list,
            'class'       => 'home-repeater-fields-hs category-fields'
        ),
        'section_category_3' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Category Three', 'mate' ),
            'options'     => $mate_post_category_list,
            'class'       => 'home-repeater-fields-hs category-fields'
        ),
        'section_category_4' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Category Four', 'mate' ),
            'options'     => $mate_post_category_list,
            'class'       => 'home-repeater-fields-hs category-fields'
        ),
        'column_number' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Column Number', 'mate' ),
            'options'     => $column_number,
            'class'       => 'home-repeater-fields-hs grid-fields'
        ),
        'ed_excerpt_content' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Excerpt Content', 'mate' ),
            'class'       => 'home-repeater-fields-hs latest-fields banner-fields grid-fields carousel-fields mix-grid-fields you-may-also-like-fields'
        ),
        'ed_category_meta' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Category Meta', 'mate' ),
            'options'     => $ed_meta_option,
            'class'       => 'home-repeater-fields-hs banner-fields latest-fields grid-fields carousel-fields mix-grid-fields you-may-also-like-fields'
        ),
        'ed_category_meta_1' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Category Meta', 'mate' ),
            'class'       => 'home-repeater-fields-hs latest-fields'
        ),
        'ed_author_meta' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Author Meta', 'mate' ),
            'class'       => 'home-repeater-fields-hs latest-fields category-fields banner-fields grid-fields carousel-fields mix-grid-fields you-may-also-like-fields'
        ),
        'ed_date_meta' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Date Meta', 'mate' ),
            'class'       => 'home-repeater-fields-hs latest-fields category-fields banner-fields grid-fields carousel-fields mix-grid-fields you-may-also-like-fields'
        ),
        'background_color' => array(
            'type'        => 'colorpicker',
            'label'       => esc_html__( 'Background Color', 'mate' ),
            'class'       => 'home-repeater-fields-hs category-fields'
        ),
        'ed_slider_navigation' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Slider Navigation', 'mate' ),
            'class'       => 'home-repeater-fields-hs carousel-fields'
        ),
        'ed_slider_pagination' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Slider Pagination', 'mate' ),
            'class'       => 'home-repeater-fields-hs carousel-fields'
        ),
        'ed_slider_autoplay' => array(
            'type'        => 'checkbox',
            'label'       => esc_html__( 'Enable Slider Autoplay', 'mate' ),
            'class'       => 'home-repeater-fields-hs carousel-fields'
        ),
        'latest_post_sidebar' => array(
            'type'        => 'select',
            'label'       => esc_html__( 'Sidebar Layout', 'mate' ),
            'options'     => $sidebar_layout,
            'class'       => 'home-repeater-fields-hs latest-fields'
        ),
        
    )
));

// Customizer Message Pro.
$wp_customize->add_setting(
    'mate_notiece_pro',
    array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field'
    )
);
$wp_customize->add_control(
    new Mate_Notiece_Control( 
        $wp_customize,
        'mate_notiece_pro',
        array(
            'settings' => 'mate_notiece_pro',
            'section'       => 'home_sections_repeater',
            'label'         => esc_html__( 'More Blocks available on Premium version.', 'mate' ),
        )
    )
);

// Info.
$wp_customize->add_setting(
    'mate_notiece_info',
    array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field'
    )
);
$wp_customize->add_control(
    new Mate_Info_Notiece_Control( 
        $wp_customize,
        'mate_notiece_info',
        array(
            'settings' => 'mate_notiece_info',
            'section'       => 'home_sections_repeater',
            'label'         => esc_html__( 'Info', 'mate' ),
        )
    )
);