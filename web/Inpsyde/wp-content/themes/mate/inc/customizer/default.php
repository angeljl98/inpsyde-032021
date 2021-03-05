<?php
/**
 * Default Values.
 *
 * @package Mate
 */

if ( ! function_exists( 'mate_get_default_theme_options' ) ) :

	/**
	 * Get default theme options
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function mate_get_default_theme_options() {

		$mate_defaults = array();
		
		$mate_defaults['twp_mate_home_sections_1'] 	= array(

            array(
                'home_section_type' => 'banner',
                'section_ed'        => 'no',
                'section_post_category'   => '',
                'ed_category_meta'        => 'all-cat',
                'ed_author_meta'        => 'yes',
                'ed_date_meta'        => 'yes',
                'ed_excerpt_content' => 'no',
            ),
            array(
                'home_section_type' => 'grid',
                'section_ed'        => 'no',
                'section_title'   => '',
                'section_sub_title'   => '',
                'section_post_category'   => '',
                'column_number'   => 4,
                'ed_category_meta'        => 'all-cat',
                'ed_author_meta'        => 'yes',
                'ed_date_meta'        => 'yes',
                'ed_excerpt_content' => 'no',
            ),
            array(
                'home_section_type' => 'carousel',
                'section_ed'        => 'no',
                'section_title'   => '',
                'section_sub_title'   => '',
                'section_post_category'   => '',
                'ed_slider_navigation'   => 'yes',
                'ed_slider_pagination'   => 'no',
                'ed_slider_autoplay'   => 'yes',
                'ed_category_meta'        => 'all-cat',
                'ed_author_meta'        => 'yes',
                'ed_date_meta'        => 'yes',
                'ed_excerpt_content' => 'no',
            ),
            array(
                'home_section_type' => 'mix-grid',
                'section_ed'        => 'no',
                'section_title'   => '',
                'section_sub_title'   => '',
                'section_post_category'   => '',
                'ed_category_meta'        => 'all-cat',
                'ed_author_meta'        => 'yes',
                'ed_date_meta'        => 'yes',
                'ed_excerpt_content' => 'yes',
            ),
            array(
                'home_section_type' => 'latest',
                'section_ed'        => 'yes',
                'latest_post_sidebar'        => 'right-sidebar',
                'ed_category_meta_1'        => 'yes',
                'ed_author_meta'        => 'yes',
                'ed_date_meta'        => 'yes',
                'ed_excerpt_content' => 'yes',
            ),
            array(
                'home_section_type' => 'category',
                'section_ed'        => 'no',
                'ed_author_meta'        => 'yes',
                'ed_date_meta'        => 'yes',
                'background_color'        => '#f9e3d2',
                'category_article_title'   => esc_html__( 'Article Posts:', 'mate' ),
            ),
            array(
                'home_section_type' => 'you-may-also-like',
                'section_ed'        => 'no',
                'section_title'   => esc_html__( 'You may also Like', 'mate' ),
                'section_sub_title'   => esc_html__( 'Recommended Posts', 'mate' ),
                'section_post_category'   => '',
                'ed_category_meta'        => 'all-cat',
                'ed_author_meta'        => 'yes',
                'ed_date_meta'        => 'yes',
                'ed_excerpt_content' => 'yes',
            ),
            
        );

		// Options.
        $mate_defaults['header_text']                               = esc_html__('Hello World!','mate');
        $mate_defaults['header_button_label']                       = esc_html__('Sign Up','mate');
		$mate_defaults['global_sidebar_layout']						= 'right-sidebar';
        $mate_defaults['global_single_sidebar_layout']              = 'right-sidebar';
		$mate_defaults['mate_pagination_layout']					= 'numeric';
		$mate_defaults['footer_column_layout'] 						= 3;
		$mate_defaults['footer_copyright_text'] 					= esc_html__( 'All rights reserved.', 'mate' );
		$mate_defaults['ed_header_search'] 							= 1;
        $mate_defaults['ed_overlap_header']                         = 1;
        $mate_defaults['ed_day_night_mode_switch']                  = 1;
        $mate_defaults['ed_responsive_menu']                        = 1;
        $mate_defaults['ed_header_search_recent_posts']             = 1;
        $mate_defaults['ed_header_search_top_category']             = 1;
		$mate_defaults['ed_image_content_inverse'] 					= 0;
		$mate_defaults['mate_heading_language'] 					= 'latin';
		$mate_defaults['ed_related_post']                			= 1;
        $mate_defaults['related_post_title']             			= esc_html__('Related Post','mate');
        $mate_defaults['recent_post_title_search']                 = esc_html__('Recent Post','mate');
        $mate_defaults['top_category_title_search']                 = esc_html__('Top Category','mate');
        $mate_defaults['twp_navigation_type']              			= 'norma-navigation';
        $mate_defaults['mate_single_post_layout']              		= 'layout-1';
        $mate_defaults['ed_post_author']                			= 1;
        $mate_defaults['ed_post_date']                				= 1;
        $mate_defaults['ed_post_category']                			= 1;
        $mate_defaults['ed_post_tags']                				= 1;
        $mate_defaults['ed_floating_next_previous_nav']             = 1;
        $mate_defaults['footer_background_color']               	= '#000000';
        $mate_defaults['footer_text_color']               			= '#fff';
        $mate_defaults['ed_header_image']                           = 1;
        $mate_defaults['ed_preloader']                              = 1;
        $mate_defaults['mate_primary_color']               			= '#F44336';
        $mate_defaults['mate_secondary_color']               		= '#FF9800';
        $mate_defaults['mate_general_text_color']                   = '#000000';
        $mate_defaults['background_color']                          = 'ffffff';
        $mate_defaults['mate_color_schema']                         = 'simple';
        $mate_defaults['global_single_gradient_overlay_color']      = '1';
        $mate_defaults['global_archive_gradient_overlay_color']      = '1';


		// Pass through filter.
		$mate_defaults = apply_filters( 'mate_filter_default_theme_options', $mate_defaults );

		return $mate_defaults;

	}

endif;
