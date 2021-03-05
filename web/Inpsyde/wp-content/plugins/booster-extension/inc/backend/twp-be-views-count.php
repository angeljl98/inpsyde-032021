<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !function_exists('booster_extension_get_post_view') ):

    function booster_extension_get_post_view( $data = true ) {
        
        $twp_be_settings = get_option('twp_be_options_settings');
        $twp_be_enable_post_visit_tracking = isset( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) ? esc_html( $twp_be_settings[ 'twp_be_enable_post_visit_tracking' ] ) : '';
            
            
        if( $twp_be_enable_post_visit_tracking ){

            $twp_be_settings = get_option('twp_be_options_settings');
            $twp_be_views_label = isset( $twp_be_settings[ 'twp_be_views_label' ] ) ? esc_html( $twp_be_settings[ 'twp_be_views_label' ] ) : esc_html__('Views','booster-extension');

            if( isset( $data['count_only'] ) && $data['count_only'] == 'count' ) {

                if( isset( $data['label'] ) &&  $data['label'] ){
                    $label = '<span class="views-count-label">'.esc_html( $data['label'] ).'</span>';
                }else{
                    $label = '';
                }
                return absint( get_post_meta( get_the_ID(), 'twp_be_post_views_count', true ) ).$label;

            }

            if( isset( $data['container'] ) && $data['container'] == 'true' ){

                ob_start();
                

                echo "<div class='twp-post-visit'>";
                echo "<span>".$twp_be_views_label."</span>";
                echo absint( get_post_meta( get_the_ID(), 'twp_be_post_views_count', true ) );
                echo "</div>";
                $html = ob_get_contents();
                ob_get_clean();
                return $html;

            }else{
                echo esc_html( $twp_be_views_label ).' '.absint( get_post_meta( get_the_ID(), 'twp_be_post_views_count', true ) );
            }
        
        }else{

            echo '';
        }

    }

endif;

if( !function_exists('booster_extension_set_post_view') ):

    // Track Count While Visit Page
    function booster_extension_set_post_view() {

        $key = 'twp_be_post_views_count';
        $post_id = get_the_ID();
        $count = absint( get_post_meta( $post_id, $key, true ) );
        $count++;
        update_post_meta( $post_id, $key, $count );

        $date = esc_html( current_time('Y-m-d') );
        $visited_data = array();
        $visited_data =  get_option('twp_visited_date');
        
        if( empty( $visited_data) ){
            $visited_data = array();
        }

        if( array_key_exists( $date,$visited_data ) ){
            
            $i = 0;
            $id_array = array();

            foreach( $visited_data[$date] as $value ){
                
                $id_array[] = absint( $value['post_id'] );

                if( $value['post_id'] == $post_id ){

                    $value['post_id'];
                    $newvisit = absint( $value['twp_be_post_visited']+1 );
                    $visited_data[$date][$i]['twp_be_post_visited'] = absint( $newvisit );

                }

            $i++;
            }

            if( !in_array($post_id, $id_array) ){

                $visited_data[$date][] = array('post_id' => absint( $post_id ), 'twp_be_post_visited' => '1' );
            }

        }else{
            $visited_data[$date] = array(
                    array('post_id' => absint( $post_id ), 'twp_be_post_visited' => '1' ),
                );
        }

        $visited =  array( 
            $date => array(
                array('post_id' => absint( $post_id ), 'twp_be_post_visited' => '1' ),
            ),
        );

        $visited_data = array_slice($visited_data, 0, 100, true);

        update_option('twp_visited_date',$visited_data);

    }

endif;

if( !function_exists('booster_extension_posts_visits') ):

    /**
     * Post Visit Get 
    **/
    function booster_extension_posts_visits($days){

        $visited = array();
        $visited =  get_option('twp_visited_date');
        $id_views_lists = array();

        if( $visited ){

            foreach( $visited as $key => $value ){
                

                $now = time();
                $your_date = strtotime( $key );
                $datediff = $now - $your_date;
                $datediff = round($datediff / (60 * 60 * 24));

                if( $datediff <= $days ){

                    foreach( $value as $value2 ){

                        if( array_key_exists( $value2['post_id'], $id_views_lists) ){

                            $visited_posts = absint( $id_views_lists[$value2['post_id']] ) + absint( $value2['twp_be_post_visited'] );
                            $id_views_lists[ $value2['post_id'] ] = absint( $visited_posts );

                        }else{
                            $id_views_lists[ $value2['post_id'] ] = absint( $value2['twp_be_post_visited'] );
                        }
                    }

                }

            }
        }
        return $id_views_lists;

    }

endif;

if( !function_exists('booster_extension_posts_column_views') ):

    // Column Name
    function booster_extension_posts_column_views( $columns ) {

        $columns['twp_be_post_views'] = esc_html__('Views','booster-extension');
        return $columns;

    }

endif;

if( !function_exists('booster_extension_posts_custom_column_views') ):

    // Show views count on post list column
    function booster_extension_posts_custom_column_views( $column ) {

        if ( $column === 'twp_be_post_views') {
            booster_extension_get_post_view( $html = false );
        }

    }

endif;
add_filter( 'manage_posts_columns', 'booster_extension_posts_column_views' );
add_action( 'manage_posts_custom_column', 'booster_extension_posts_custom_column_views' );