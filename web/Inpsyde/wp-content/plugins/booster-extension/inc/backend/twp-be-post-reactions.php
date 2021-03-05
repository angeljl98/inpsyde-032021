<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Post Reactions
 *
 * @package Booster Extension
**/

add_action('wp_ajax_booster_extension_post_react', 'booster_extension_post_react_callback');
add_action('wp_ajax_nopriv_booster_extension_post_react', 'booster_extension_post_react_callback');

if( ! function_exists( 'booster_extension_post_react_callback' ) ) :

    // Recommendec Post Ajax Call Function.
    function booster_extension_post_react_callback() {
        
        if ( isset($_POST[ '_wpnonce' ]) && wp_verify_nonce($_POST[ '_wpnonce' ], 'be_ajax_nonce') ) {
            $post_ID = $_POST['postID'];
            $action_type = $_POST['reactdata'];
            $react_1   = ( $react_1_count = get_post_meta( $post_ID, 'twp_be_react_1', true ) ) ? $react_1_count : 0;
            $react_1_ip_lists = ( $react_1_ips = get_post_meta( $post_ID, 'twp_be_ip_address_react_1', true ) ) ? $react_1_ips : array();
            
            $react_2   = ( $react_2_count = get_post_meta( $post_ID, 'twp_be_react_2', true ) ) ? $react_2_count : 0;
            $react_2_ip_lists = ( $react_2_ips = get_post_meta( $post_ID, 'twp_be_ip_address_react_2', true ) ) ? $react_2_ips : array();
            $react_3   = ( $react_3_count = get_post_meta( $post_ID, 'twp_be_react_3', true ) ) ? $react_3_count : 0;
            $react_3_ip_lists = ( $react_3_ips = get_post_meta( $post_ID, 'twp_be_ip_address_react_3', true ) ) ? $react_3_ips : array();
            $react_4   = ( $react_4_count = get_post_meta( $post_ID, 'twp_be_react_4', true ) ) ? $react_4_count : 0;
            $react_4_ip_lists = ( $react_4_ips = get_post_meta( $post_ID, 'twp_be_ip_address_react_4', true ) ) ? $react_4_ips : array();
            $react_5   = ( $react_5_count = get_post_meta( $post_ID, 'twp_be_react_5', true ) ) ? $react_5_count : 0;
            $react_5_ip_lists = ( $react_5_ips = get_post_meta( $post_ID, 'twp_be_ip_address_react_5', true ) ) ? $react_5_ips : array();
            $react_6   = ( $react_6_count = get_post_meta( $post_ID, 'twp_be_react_6', true ) ) ? $react_6_count : 0;
            $react_6_ip_lists = ( $react_6_ips = get_post_meta( $post_ID, 'twp_be_ip_address_react_6', true ) ) ? $react_6_ips : array();
            
            if( $action_type == 'be-react-1' ){
                if( booster_extension_can_act( $post_ID,$action_type ) ){
                    $react_1  = $react_1 + 1;
                    $react_1_ip_lists[] = booster_extension_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_react_1', $react_1 );
                    update_post_meta( $post_ID, 'twp_be_ip_address_react_1', $react_1_ip_lists );
                    if( !booster_extension_can_act( $post_ID,'be-react-2' ) ){
                        if( $react_2_ips ){
                            $react_2     = $react_2 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_2_ip_lists = booster_remove_element($react_2_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_2', $react_2 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_2', $react_2_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-3' ) ){
                        if( $react_3_ips ){
                            $react_3     = $react_3 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_3_ip_lists = booster_remove_element($react_3_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_3', $react_3 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_3', $react_3_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-4' ) ){
                        if( $react_4_ips ){
                            $react_4     = $react_4 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_4_ip_lists = booster_remove_element($react_4_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_4', $react_4 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_4', $react_4_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-5' ) ){
                        if( $react_5_ips ){
                            $react_5     = $react_5 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_5_ip_lists = booster_remove_element($react_5_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_5', $react_5 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_5', $react_5_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-6' ) ){
                        if( $react_6_ips ){
                            $react_6     = $react_6 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_6_ip_lists = booster_remove_element($react_6_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_6', $react_6 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_6', $react_6_ip_lists );
                        }
                    }
                }else{
                    if( $react_1_ip_lists ){
                        $react_1 = $react_1 - 1;
                        $current_ip = booster_extension_get_ip_address();
                        $react_1_ip_lists = booster_remove_element( $react_1_ip_lists, $current_ip );
                        update_post_meta( $post_ID, 'twp_be_react_1', $react_1 );
                        update_post_meta( $post_ID, 'twp_be_ip_address_react_1', $react_1_ip_lists );
                    }
                }
            }elseif( $action_type == 'be-react-2' ){
                if( booster_extension_can_act( $post_ID,$action_type ) ){
                    $react_2  = $react_2 + 1;
                    $react_2_ip_lists[] = booster_extension_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_react_2', $react_2 );
                    update_post_meta( $post_ID, 'twp_be_ip_address_react_2', $react_2_ip_lists );
                    if( !booster_extension_can_act( $post_ID,'be-react-1' ) ){
                        if( $react_1_ips ){
                            $react_1     = $react_1 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_1_ip_lists = booster_remove_element($react_1_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_1', $react_1 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_1', $react_1_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-3' ) ){
                        if( $react_3_ips ){
                            $react_3     = $react_3 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_3_ip_lists = booster_remove_element($react_3_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_3', $react_3 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_3', $react_3_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-4' ) ){
                        if( $react_4_ips ){
                            $react_4     = $react_4 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_4_ip_lists = booster_remove_element($react_4_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_4', $react_4 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_4', $react_4_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-5' ) ){
                        if( $react_5_ips ){
                            $react_5     = $react_5 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_5_ip_lists = booster_remove_element($react_5_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_5', $react_5 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_5', $react_5_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-6' ) ){
                        if( $react_6_ips ){
                            $react_6     = $react_6 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_6_ip_lists = booster_remove_element($react_6_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_6', $react_6 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_6', $react_6_ip_lists );
                        }
                    }
                }else{
                    if( $react_2_ip_lists ){
                        $react_2 = $react_2 - 1;
                        $current_ip = booster_extension_get_ip_address();
                        $react_2_ip_lists = booster_remove_element( $react_2_ip_lists, $current_ip );
                        update_post_meta( $post_ID, 'twp_be_react_2', $react_2 );
                        update_post_meta( $post_ID, 'twp_be_ip_address_react_2', $react_2_ip_lists );
                    }
                }
            }elseif( $action_type == 'be-react-3' ){
                if( booster_extension_can_act( $post_ID,$action_type ) ){
                    $react_3  = $react_3 + 1;
                    $react_3_ip_lists[] = booster_extension_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_react_3', $react_3 );
                    update_post_meta( $post_ID, 'twp_be_ip_address_react_3', $react_3_ip_lists );
                    if( !booster_extension_can_act( $post_ID,'be-react-1' ) ){
                        if( $react_1_ips ){
                            $react_1     = $react_1 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_1_ip_lists = booster_remove_element($react_1_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_1', $react_1 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_1', $react_1_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-2' ) ){
                        if( $react_2_ips ){
                            $react_2     = $react_2 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_2_ip_lists = booster_remove_element($react_2_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_2', $react_2 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_2', $react_2_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-4' ) ){
                        if( $react_4_ips ){
                            $react_4     = $react_4 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_4_ip_lists = booster_remove_element($react_4_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_4', $react_4 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_4', $react_4_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-5' ) ){
                        if( $react_5_ips ){
                            $react_5     = $react_5 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_5_ip_lists = booster_remove_element($react_5_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_5', $react_5 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_5', $react_5_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-6' ) ){
                        if( $react_6_ips ){
                            $react_6     = $react_6 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_6_ip_lists = booster_remove_element($react_6_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_6', $react_6 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_6', $react_6_ip_lists );
                        }
                    }
                }else{
                    if( $react_3_ip_lists ){
                        $react_3 = $react_3 - 1;
                        $current_ip = booster_extension_get_ip_address();
                        $react_3_ip_lists = booster_remove_element( $react_3_ip_lists, $current_ip );
                        update_post_meta( $post_ID, 'twp_be_react_3', $react_3 );
                        update_post_meta( $post_ID, 'twp_be_ip_address_react_3', $react_3_ip_lists );
                    }
                }
            }elseif( $action_type == 'be-react-4' ){
                if( booster_extension_can_act( $post_ID,$action_type ) ){
                    $react_4  = $react_4 + 1;
                    $react_4_ip_lists[] = booster_extension_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_react_4', $react_4 );
                    update_post_meta( $post_ID, 'twp_be_ip_address_react_4', $react_4_ip_lists );
                    if( !booster_extension_can_act( $post_ID,'be-react-1' ) ){
                        if( $react_1_ips ){
                            $react_1     = $react_1 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_1_ip_lists = booster_remove_element($react_1_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_1', $react_1 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_1', $react_1_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-2' ) ){
                        if( $react_2_ips ){
                            $react_2     = $react_2 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_2_ip_lists = booster_remove_element($react_2_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_2', $react_2 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_2', $react_2_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-3' ) ){
                        if( $react_3_ips ){
                            $react_3     = $react_3 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_3_ip_lists = booster_remove_element($react_3_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_3', $react_3);
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_3', $react_3_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-5' ) ){
                        if( $react_5_ips ){
                            $react_5     = $react_5 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_5_ip_lists = booster_remove_element($react_5_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_5', $react_5 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_5', $react_5_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-6' ) ){
                        if( $react_6_ips ){
                            $react_6     = $react_6 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_6_ip_lists = booster_remove_element($react_6_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_6', $react_6 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_6', $react_6_ip_lists );
                        }
                    }
                }else{
                    if( $react_4_ip_lists ){
                        $react_4 = $react_4 - 1;
                        $current_ip = booster_extension_get_ip_address();
                        $react_4_ip_lists = booster_remove_element( $react_4_ip_lists, $current_ip );
                        update_post_meta( $post_ID, 'twp_be_react_4', $react_4 );
                        update_post_meta( $post_ID, 'twp_be_ip_address_react_4', $react_4_ip_lists );
                    }
                }
            }elseif( $action_type == 'be-react-5' ){
                if( booster_extension_can_act( $post_ID,$action_type ) ){
                    $react_5  = $react_5 + 1;
                    $react_5_ip_lists[] = booster_extension_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_react_5', $react_5 );
                    update_post_meta( $post_ID, 'twp_be_ip_address_react_5', $react_5_ip_lists );
                    if( !booster_extension_can_act( $post_ID,'be-react-1' ) ){
                        if( $react_1_ips ){
                            $react_1     = $react_1 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_1_ip_lists = booster_remove_element($react_1_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_1', $react_1 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_1', $react_1_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-2' ) ){
                        if( $react_2_ips ){
                            $react_2     = $react_2 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_2_ip_lists = booster_remove_element($react_2_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_2', $react_2 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_2', $react_2_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-3' ) ){
                        if( $react_3_ips ){
                            $react_3     = $react_3 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_3_ip_lists = booster_remove_element($react_3_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_3', $react_3);
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_3', $react_3_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-4' ) ){
                        if( $react_4_ips ){
                            $react_4     = $react_4 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_4_ip_lists = booster_remove_element($react_4_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_4', $react_4 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_4', $react_4_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-6' ) ){
                        if( $react_6_ips ){
                            $react_6     = $react_6 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_6_ip_lists = booster_remove_element($react_6_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_6', $react_6 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_6', $react_6_ip_lists );
                        }
                    }
                }else{
                    if( $react_5_ip_lists ){
                        $react_5 = $react_5 - 1;
                        $current_ip = booster_extension_get_ip_address();
                        $react_5_ip_lists = booster_remove_element( $react_5_ip_lists, $current_ip );
                        update_post_meta( $post_ID, 'twp_be_react_5', $react_5 );
                        update_post_meta( $post_ID, 'twp_be_ip_address_react_5', $react_5_ip_lists );
                    }
                }
            }elseif( $action_type == 'be-react-6' ){

                if( booster_extension_can_act( $post_ID,$action_type ) ){
                    $react_6  = $react_6 + 1;
                    $react_6_ip_lists[] = booster_extension_get_ip_address();
                    
                    update_post_meta( $post_ID, 'twp_be_react_6', $react_6 );
                    update_post_meta( $post_ID, 'twp_be_ip_address_react_6', $react_6_ip_lists );

                    if( !booster_extension_can_act( $post_ID,'be-react-1' ) ){
                        if( $react_1_ips ){
                            $react_1     = $react_1 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_1_ip_lists = booster_remove_element($react_1_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_1', $react_1 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_1', $react_1_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-2' ) ){
                        if( $react_2_ips ){
                            $react_2     = $react_2 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_2_ip_lists = booster_remove_element($react_2_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_2', $react_2 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_2', $react_2_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-3' ) ){
                        if( $react_3_ips ){
                            $react_3     = $react_3 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_3_ip_lists = booster_remove_element($react_3_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_3', $react_3);
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_3', $react_3_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-4' ) ){
                        if( $react_4_ips ){
                            $react_4     = $react_4 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_4_ip_lists = booster_remove_element($react_4_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_4', $react_4 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_4', $react_4_ip_lists );
                        }
                    }
                    if( !booster_extension_can_act( $post_ID,'be-react-5' ) ){
                        if( $react_5_ips ){
                            $react_5     = $react_5 - 1;
                            $current_ip = booster_extension_get_ip_address();
                            $react_5_ip_lists = booster_remove_element($react_5_ips, $current_ip);
                            update_post_meta( $post_ID, 'twp_be_react_5', $react_5 );
                            update_post_meta( $post_ID, 'twp_be_ip_address_react_5', $react_5_ip_lists );
                        }
                    }
                }else{
                    if( $react_6_ip_lists ){
                        $react_6 = $react_6 - 1;
                        $current_ip = booster_extension_get_ip_address();
                        $react_6_ip_lists = booster_remove_element( $react_6_ip_lists, $current_ip );
                        update_post_meta( $post_ID, 'twp_be_react_6', $react_6 );
                        update_post_meta( $post_ID, 'twp_be_ip_address_react_6', $react_6_ip_lists );
                    }
                }
            }
        }
    }
    
endif;