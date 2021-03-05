<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Like Dislike Metabox.
*
* @package Booster Extension
*/
 
add_action( 'add_meta_boxes', 'booster_extension_like_dislike_count_metabox' );
if( ! function_exists( 'booster_extension_like_dislike_count_metabox' ) ):

    function  booster_extension_like_dislike_count_metabox() {
        
        add_meta_box(
            'booster_extension_post_like_dislike_count_metabox',
            esc_html__( 'Like/Dislike Count', 'booster-extension' ),
            'booster_extension_post_like_count',
            'post', 
            'normal', 
            'high'
        );
    }

endif;

if( ! function_exists( 'booster_extension_post_like_count' ) ):

    /**
     * Callback function for post option.
    */
	function booster_extension_post_like_count() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'booster_extension_like_dislike_meta_nonce' ); ?>
        <table class="form-table">
            <tr>
                <td>
                    <?php
                    $twp_be_like_count = 0;
                    $twp_be_like_count = absint( get_post_meta( $post->ID, 'twp_be_like_count', true ) );
                    echo absint( $twp_be_like_count ).esc_html__(' Likes','booster-extension');
                    ?>
                    <div class="clear"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    $twp_be_dislike_like_count = 0;
                    $twp_be_dislike_like_count = absint( get_post_meta( $post->ID, 'twp_be_dislike_like_count', true ) ).esc_html__(' Dislikes','booster-extension'); 
                    echo absint( $twp_be_dislike_like_count ).esc_html__(' Dislikes','booster-extension');
                    ?>
                    <div class="clear"></div>
                </td>
            </tr>
        </table>
    <?php }

endif;

if( ! function_exists( 'booster_extension_like_dislike_column_count' ) ):

    /**
     * Display custom column
    **/
    function booster_extension_like_dislike_column_count( $column, $post_id ) {
        if ( $column == 'like-dislike-count' ){
            echo '<div class="twp-column-count">';
            echo '<span class="twp-column-likedislike">';
                $twp_be_like_count = 0;
                $twp_be_like_count = absint( get_post_meta( $post_id, 'twp_be_like_count', true ) );
                echo absint( $twp_be_like_count ).esc_html__(' Likes','booster-extension');
            echo '</span>';
            echo '<span class="twp-column-likedislike">';
                $twp_be_dislike_like_count = 0;
                $twp_be_dislike_like_count = absint( get_post_meta( $post_id, 'twp_be_dislike_like_count', true ) ).esc_html__(' Dislikes','booster-extension'); 
                echo absint( $twp_be_dislike_like_count ).esc_html__(' Dislikes','booster-extension');
            echo '</span>';
            echo '</div>';
        }
    }

endif;

add_action( 'manage_post_posts_custom_column' , 'booster_extension_like_dislike_column_count', 10, 2 );

if( ! function_exists( 'booster_extension_like_dislike_count_column_title' ) ):

    /**
     * Add custom column to post list.
    **/
    function booster_extension_like_dislike_count_column_title( $columns ) {
        return array_merge( $columns, array( 'like-dislike-count' => esc_html__( 'Like/Dislike', 'booster-extension' ) ) );
    }

endif;
add_filter( 'manage_post_posts_columns' , 'booster_extension_like_dislike_count_column_title' );