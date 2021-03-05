<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Read Time
*
* @package Booster Extension
*/

if( ! function_exists( 'booster_extension_read_time_display' ) ):

    function booster_extension_read_time_display(){

        global $post;
        if( ( isset( $post->post_content ) && $post->post_content ) ){

            $twp_be_settings = get_option( 'twp_be_options_settings' );
            $twp_be_enable_read_time = isset( $twp_be_settings[ 'twp_be_enable_read_time' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_read_time' ] ) : '';
            $word_per_minute = isset( $twp_be_settings[ 'twp_be_readtime_word_per_minute' ] ) ? absint( $twp_be_settings[ 'twp_be_readtime_word_per_minute' ] ) : '';
            $twp_be_readtime_label = isset( $twp_be_settings[ 'twp_be_readtime_label' ] ) ? esc_html( $twp_be_settings[ 'twp_be_readtime_label' ] ) : esc_html__('Read Time','booster-extension');
            $twp_be_enable_second = isset( $twp_be_settings[ 'twp_be_enable_second' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_second' ] ) : 1;
            $twp_be_minute_label = isset( $twp_be_settings[ 'twp_be_minute_label' ] ) ? esc_html( $twp_be_settings[ 'twp_be_minute_label' ] ) : esc_html__('Minute','booster-extension');
            $twp_be_second_label = isset( $twp_be_settings[ 'twp_be_second_label' ] ) ? esc_html( $twp_be_settings[ 'twp_be_second_label' ] ) : esc_html__('Second','booster-extension');

            if( $twp_be_enable_read_time ){ ?>

                <div class="twp-read-time">
                	<?php
                    if( $post->post_content ){

                        $words = count( preg_split('~[^\p{L}\p{N}\']+~u',strip_tags( $post->post_content ) ) );
                        $minutes = floor( $words / $word_per_minute );
                        $seconds = floor( $words % $word_per_minute / ( $word_per_minute / 60 ) );

                        $estimated_time = '';
                        if ( 1 <= $minutes ) {

                            $estimated_time = $minutes.' '.$twp_be_minute_label;

                            if( $twp_be_enable_second ){
                                $estimated_time .= ', ' . $seconds.' '.$twp_be_second_label;
                            }
                        } else {

                            if( $twp_be_enable_second ){
                                $estimated_time = $seconds.' '.$twp_be_second_label;
                            }
                        }

                        if( $estimated_time ){
                            
                            echo '<i class="booster-icon twp-clock"></i> ';
                            echo '<span>'.$twp_be_readtime_label.'</span>';
                            echo esc_html( $estimated_time );

                        }

                    }
                    ?>
                </div>

            <?php 
            }

        }
    }

endif;

booster_extension_read_time_display(); ?>