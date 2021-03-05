<?php
/**
* Body Classes.
*
* @package Mate
*/
 
 if (!function_exists('mate_body_classes')) :

    function mate_body_classes($classes) {

        $mate_default = mate_get_default_theme_options();
        global $post;
        // Adds a class of hfeed to non-singular pages.
        if ( !is_singular() ) {
            $classes[] = 'hfeed';
        }

        if( is_single() || is_page() ){

            $sidebar = esc_attr( get_post_meta( $post->ID, 'mate_post_sidebar_option', true ) );
            if( empty( $sidebar ) || $sidebar == 'global-sidebar' ){

                $sidebar = get_theme_mod( 'global_single_sidebar_layout',$mate_default['global_single_sidebar_layout'] );

            }

        }elseif( is_front_page() ){
            
            $twp_mate_home_sections_1 = get_theme_mod( 'twp_mate_home_sections_1',json_encode( $mate_default['twp_mate_home_sections_1'] ) );
            $twp_mate_home_sections_1 = json_decode( $twp_mate_home_sections_1 );

            if( $twp_mate_home_sections_1 ){

                foreach( $twp_mate_home_sections_1 as $twp_mate_home_section ){

                    $home_section_type = isset( $twp_mate_home_section->home_section_type ) ? $twp_mate_home_section->home_section_type : '' ;

                    switch( $home_section_type ){

                        case 'latest':

                            $sidebar = isset( $twp_mate_home_section->latest_post_sidebar ) ? $twp_mate_home_section->latest_post_sidebar : '' ;
                            
                        break;

                    }

                }

            }else{

                $sidebar = get_theme_mod( 'global_sidebar_layout',$mate_default['global_sidebar_layout'] );

            }

        }else{
            
            $sidebar = get_theme_mod( 'global_sidebar_layout',$mate_default['global_sidebar_layout'] );

        }

        $classes[] = esc_attr( $sidebar );

        if( $sidebar == 'both-sidebar' || $sidebar == 'content-left-right' || $sidebar == 'left-right-content' ){

            if( is_active_sidebar('sidebar-1') && is_active_sidebar('mate-left-sidebar') ){

                $classes[] = 'content-column-3';

            }elseif( is_active_sidebar('sidebar-1') || is_active_sidebar('mate-left-sidebar') ){
                
                $classes[] = 'content-column-2';

            }else{

                $classes[] = 'content-column-1';

            }

        }elseif( $sidebar == 'right-sidebar' ){

            if( is_active_sidebar('sidebar-1') ){

                $classes[] = 'content-column-2';

            }else{

                $classes[] = 'content-column-1';

            }

        }elseif( $sidebar == 'left-sidebar' ){

            if( is_active_sidebar('mate-left-sidebar') ){

                $classes[] = 'content-column-2';

            }else{

                $classes[] = 'content-column-1';

            }

        }elseif( $sidebar == 'no-sidebar' ){

            $classes[] = 'content-column-1';

        }else{

            if( is_active_sidebar('sidebar-1') ){

                $classes[] = 'content-column-2';

            }else{

                $classes[] = 'content-column-1';

            }

        }


        if( is_singular('post') ){

            $mate_post_layout = esc_attr( get_post_meta( $post->ID, 'mate_post_layout', true ) );

            if( $mate_post_layout == '' || $mate_post_layout == 'global-layout' ){
                
                $mate_post_layout = get_theme_mod( 'mate_single_post_layout',$mate_default['mate_single_post_layout'] );

            }

        }

        if( is_singular('post') ){

            $mate_ed_post_reaction = esc_attr( get_post_meta( $post->ID, 'mate_ed_post_reaction', true ) );
            if( $mate_ed_post_reaction ){
                $classes[] = 'hide-comment-rating';
            }

        }
        
        if( has_header_image() || get_header_video_url() ){

            $classes[] = 'has-header-media';

            if( get_header_video_url() ){

                $classes[] = 'has-header-video';

            }else{

                $classes[] = 'has-header-image';

            }
        }

        $ed_overlap_header = get_theme_mod( 'ed_overlap_header',$mate_default['ed_overlap_header'] );
        if( $ed_overlap_header && is_home() && is_front_page() ){

            $classes[] = 'theme-header-overlay';

        }

        $mate_color_schema = get_theme_mod('mate_color_schema',$mate_default['mate_color_schema'] );
        $classes[] = 'theme-color-schema';
        $classes[] = esc_attr( 'theme-color-schema-'.$mate_color_schema );

        return $classes;
    }

endif;

add_filter('body_class', 'mate_body_classes');