<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Mate
 * @since 1.0.0
 */
get_header();
    
    $mate_default = mate_get_default_theme_options();
    $twp_mate_home_sections_1 = get_theme_mod( 'twp_mate_home_sections_1',json_encode( $mate_default['twp_mate_home_sections_1'] ) );
    $twp_mate_home_sections_1 = json_decode( $twp_mate_home_sections_1 );

    $paged_active = false;
    if ( !is_paged() ) {

        $paged_active = true;

    }

    if( $twp_mate_home_sections_1 ){

        foreach( $twp_mate_home_sections_1 as $twp_mate_home_section ){

            $home_section_type = isset( $twp_mate_home_section->home_section_type ) ? $twp_mate_home_section->home_section_type : '' ;

            switch( $home_section_type ){

                case 'banner':

                    $section_ed = isset( $twp_mate_home_section->section_ed ) ? $twp_mate_home_section->section_ed : '' ;

                    if( is_front_page() && $section_ed == 'yes' && $paged_active ){

                        mate_fullwidth_slider( $twp_mate_home_section );

                    }

                break;

                case 'grid':

                    $section_ed = isset( $twp_mate_home_section->section_ed ) ? $twp_mate_home_section->section_ed : '' ;

                    if( is_front_page() && $section_ed == 'yes' && $paged_active ){

                        mate_grid_posts( $twp_mate_home_section );

                    }

                break;

                case 'carousel':

                    $section_ed = isset( $twp_mate_home_section->section_ed ) ? $twp_mate_home_section->section_ed : '' ;

                    if( is_front_page() && $section_ed == 'yes' && $paged_active ){

                        mate_carousel_posts( $twp_mate_home_section );

                    }

                break;

                case 'mix-grid':

                    $section_ed = isset( $twp_mate_home_section->section_ed ) ? $twp_mate_home_section->section_ed : '' ;

                    if( is_front_page() && $section_ed == 'yes' && $paged_active ){

                        mate_mix_grid_posts( $twp_mate_home_section );

                    }

                break;

                case 'you-may-also-like':

                    $section_ed = isset( $twp_mate_home_section->section_ed ) ? $twp_mate_home_section->section_ed : '' ;

                    if( is_front_page() && $section_ed == 'yes' && $paged_active ){

                        mate_receommended_posts( $twp_mate_home_section );

                    }

                break;

                case 'latest':

                    $section_ed = isset( $twp_mate_home_section->section_ed ) ? $twp_mate_home_section->section_ed : '' ;
                    
                    if( $section_ed == 'yes' || is_archive() || is_search() ){

                        mate_latest_posts( $twp_mate_home_section );

                    }

                break;

                case 'category':

                    $section_ed = isset( $twp_mate_home_section->section_ed ) ? $twp_mate_home_section->section_ed : '' ;

                    if( is_front_page() && $section_ed == 'yes' && $paged_active ){

                        mate_category_section( $twp_mate_home_section );

                    }

                break;

            }

        }
        
    }
    
    

get_footer();
