<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Like Dislike
*
* @package Booster Extension
*/

if( ! function_exists( 'booster_extension_like_dislike_display' ) ):

    function booster_extension_like_dislike_display( $allenable = true ){
    	
		$twp_be_settings = get_option( 'twp_be_options_settings' );
		$twp_be_enable_like_dislike_button = esc_html( $twp_be_settings['twp_be_enable_like_dislike_button'] );
		$twp_be_show_like_dislike_on_single_post = esc_html( $twp_be_settings['twp_be_show_like_dislike_on_single_post'] );
		$twp_be_show_like_dislike_on_archive = esc_html( $twp_be_settings['twp_be_show_like_dislike_on_archive'] );
		$twp_be_like_icon_layout = isset( $twp_be_settings[ 'twp_be_like_icon_layout' ] ) ? $twp_be_settings['twp_be_like_icon_layout'] : '';
		$show_like_dislike = false;


		if( is_singular('post') ){
			if( $twp_be_show_like_dislike_on_single_post ){
				$show_like_dislike = true;
			}
		}elseif( is_archive() || is_front_page() || is_home() ){
			if( $twp_be_show_like_dislike_on_archive ){
				$show_like_dislike = true;
			}
		}
		if( isset( $allenable['allenable'] ) && $allenable['allenable'] == 'allenable' ){
			$show_like_dislike = true;
			$twp_be_enable_like_dislike_button = true;
		}

		if( $twp_be_enable_like_dislike_button  && $show_like_dislike ){

			if( $twp_be_like_icon_layout == 'layout-2' ){
				$class = 'twp-like-dislike-smiley';
				$like_image = '<img src="'.esc_url( BOOSTER_EXTENSION_URL ).'/assets/icon/smiling-face.svg" />';
				$dlike_image = '<img src="'.esc_url( BOOSTER_EXTENSION_URL ).'/assets/icon/sad-face.svg" />';
			}else{
				$class = 'twp-like-dislike-thumb';
				$like_image = '<img src="'.esc_url( BOOSTER_EXTENSION_URL ).'/assets/icon/thumbs-up.svg" />';
				$dlike_image = '<img src="'.esc_url( BOOSTER_EXTENSION_URL ).'/assets/icon/thumbs-down.svg" />';
			} ?>

			<div class="twp-like-dislike-button <?php echo esc_attr( $class ); ?>">

				<?php if( booster_extension_can_act( get_the_ID(),'twp-post-like' ) ){
					$can_like = 'can-like';
				}else{
					$can_like = 'cant-like';
				} ?>

				<span data-id="<?php echo esc_attr( get_the_ID() ); ?>" id="twp-post-like" class="twp-post-like-dislike <?php echo esc_attr( $can_like ); ?>">
		            <?php echo $like_image; ?>
		        </span>

				<span class="twp-like-count">
		            <?php echo absint( get_post_meta( get_the_ID(), 'twp_be_like_count', true ) ); ?>
		        </span>

				<?php if( booster_extension_can_act( get_the_ID(),'twp-post-dislike' ) ){
					$can_dislike = 'can-dislike';
				}else{
					$can_dislike = 'cant-dislike';
				} ?>

				<span data-id="<?php echo esc_attr( get_the_ID() ); ?>" id="twp-post-dislike" class="twp-post-like-dislike <?php echo esc_attr( $can_dislike ); ?> ">
		            <?php echo $dlike_image; ?>
		        </span>

				<span class="twp-dislike-count">
		            <?php echo absint( get_post_meta( get_the_ID(), 'twp_be_dislike_like_count', true ) ); ?>
		        </span>

			</div>

		<?php 
		}

	}

endif; ?>