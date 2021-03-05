<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Functions.
 *
 * @package Booster Extension
**/

if( ! function_exists( 'booster_remove_element' ) ):

    // Remove Element
    function booster_remove_element( $array,$value ) {
         foreach ( array_keys( $array, $value ) as $key ) {
            unset( $array[$key] );
         }  
        return $array;
    }

endif;

if( ! function_exists( 'booster_extension_get_ip_address' )):

    // Get IP Address
    function booster_extension_get_ip_address(){
        if( getenv( 'HTTP_CLIENT_IP' ) ){
            $ip_address = getenv( 'HTTP_CLIENT_IP' );
        }elseif( getenv( 'HTTP_X_FORWARDED_FOR' ) ){
            $ip_address = getenv('HTTP_X_FORWARDED_FOR' );
        }elseif( getenv( 'HTTP_X_FORWARDED' ) ){
            $ip_address = getenv( 'HTTP_X_FORWARDED' );
        }elseif( getenv( 'HTTP_FORWARDED_FOR' ) ){
            $ip_address = getenv( 'HTTP_FORWARDED_FOR' );
        }elseif( getenv( 'HTTP_FORWARDED' ) ){
            $ip_address = getenv( 'HTTP_FORWARDED' );
        }else{
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip_address;
    }
endif;

if( ! function_exists( 'booster_extension_can_act' ) ) :

    // Return Ipaddress
    function booster_extension_can_act( $post_ID = false,$action_type ) {
        if( empty( $post_ID ) ){
            return false;
        }
        if( $action_type == 'twp-post-dislike' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_lists_dislike', true ) ) ? $ip  : array();
            if( ( $like_ip_list == '' ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        if( $action_type == 'twp-post-like' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_lists_like', true ) ) ? $ip  : array();
            if( ( $like_ip_list == '' ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        if( $action_type == 'be-react-1' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_react_1', true ) ) ? $ip  : array();
            if( ( empty( $like_ip_list ) ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        if( $action_type == 'be-react-2' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_react_2', true ) ) ? $ip  : array();
            if( ( $like_ip_list == '' ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        if( $action_type == 'be-react-3' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_react_3', true ) ) ? $ip  : array();
            if( ( $like_ip_list == '' ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        if( $action_type == 'be-react-4' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_react_4', true ) ) ? $ip  : array();
            if( ( $like_ip_list == '' ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        if( $action_type == 'be-react-5' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_react_5', true ) ) ? $ip  : array();
            if( ( $like_ip_list == '' ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        if( $action_type == 'be-react-6' ){
            $like_ip_list = ( $ip = get_post_meta( $post_ID, 'twp_be_ip_address_react_6', true ) ) ? $ip  : array();
            if( ( $like_ip_list == '' ) || ( is_array( $like_ip_list ) && ! in_array( booster_extension_get_ip_address(), $like_ip_list ) ) ){
                return true;
            }
        }
        return false;
    }
endif;