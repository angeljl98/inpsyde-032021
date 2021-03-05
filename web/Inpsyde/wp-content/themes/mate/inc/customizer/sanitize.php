<?php
/**
* Custom Functions.
*
* @package Mate
*/

if( !function_exists( 'mate_sanitize_sidebar_option' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_sidebar_option( $mate_input ){

        $mate_metabox_options = array( 'global-sidebar','left-sidebar','right-sidebar','no-sidebar','both-sidebar','content-left-right','left-right-content' );
        if( in_array( $mate_input,$mate_metabox_options ) ){

            return $mate_input;

        }

        return;

    }

endif;

if( !function_exists( 'mate_sanitize_single_pagination_layout' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_single_pagination_layout( $mate_input ){

        $mate_single_pagination = array( 'no-navigation','norma-navigation','ajax-next-post-load' );
        if( in_array( $mate_input,$mate_single_pagination ) ){

            return $mate_input;

        }

        return;

    }

endif;

if( !function_exists( 'mate_sanitize_archive_layout' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_archive_layout( $mate_input ){

        $mate_archive_option = array( 'default','full','grid','masonry' );
        if( in_array( $mate_input,$mate_archive_option ) ){

            return $mate_input;

        }

        return;

    }

endif;

if( !function_exists( 'mate_sanitize_header_layout' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_header_layout( $mate_input ){

        $mate_header_options = array( 'layout-1','layout-2','layout-3' );
        if( in_array( $mate_input,$mate_header_options ) ){

            return $mate_input;

        }

        return;

    }

endif;

if( !function_exists( 'mate_sanitize_single_post_layout' ) ) :

    // Single Layout Option Sanitize.
    function mate_sanitize_single_post_layout( $mate_input ){

        $mate_single_layout = array( 'layout-1','layout-2' );
        if( in_array( $mate_input,$mate_single_layout ) ){

            return $mate_input;

        }

        return;

    }

endif;

if ( ! function_exists( 'mate_sanitize_checkbox' ) ) :

	/**
	 * Sanitize checkbox.
	 */
	function mate_sanitize_checkbox( $mate_checked ) {

		return ( ( isset( $mate_checked ) && true === $mate_checked ) ? true : false );

	}

endif;


if ( ! function_exists( 'mate_sanitize_select' ) ) :

    /**
     * Sanitize select.
     */
    function mate_sanitize_select( $mate_input, $mate_setting ) {

        // Ensure input is a slug.
        $mate_input = sanitize_text_field( $mate_input );

        // Get list of choices from the control associated with the setting.
        $choices = $mate_setting->manager->get_control( $mate_setting->id )->choices;

        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $mate_input, $choices ) ? $mate_input : $mate_setting->default );

    }

endif;

if( ! function_exists( 'mate_sanitize_repeater' ) ):
    
    /**
    * Sanitise Repeater Field
    */
    function mate_sanitize_repeater( $input ){

        $input_decoded = json_decode( $input );

        if( !empty( $input_decoded ) ) {

            foreach ($input_decoded as $boxes => $box ){

                foreach ($box as $key => $value){
                    
                    if( $key == 'section_ed' ){

                        $input_decoded_1[$boxes][$key] = meta_sanitize_repeater_ed( $value );

                    }elseif( $key == 'home_section_type' ){

                        $input_decoded_1[$boxes][$key] = mate_sanitize_home_section( $value );

                    }else{

                        $input_decoded_1[$boxes][$key] = sanitize_text_field( $value );

                    }

                }

            }

            return json_encode($input_decoded_1);

        }

        return '';
    }
    
endif;

function meta_sanitize_repeater_ed( $input ) {

    $valid_keys = array('yes','no');
    if ( in_array( $input , $valid_keys ) ) {
        return $input;
    }
    return '';

}

function mate_sanitize_home_section( $input ) {

    $home_sections = mate_sanitize_home_type();

    if ( array_key_exists( $input , $home_sections ) ) {
        return $input;
    }
    return '';

}