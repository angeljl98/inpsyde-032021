<?php
/**
* Sidebar Metabox.
*
* @package Mate
*/
 
add_action( 'add_meta_boxes', 'mate_metabox' );

if( ! function_exists( 'mate_metabox' ) ):


    function  mate_metabox() {
        
        add_meta_box(
            'mate-custom-metabox',
            esc_html__( 'Layout Settings', 'mate' ),
            'mate_post_metafield_callback',
            'post', 
            'normal', 
            'high'
        );
        add_meta_box(
            'mate-custom-metabox',
            esc_html__( 'Layout Settings', 'mate' ),
            'mate_post_metafield_callback',
            'page',
            'normal', 
            'high'
        ); 
    }

endif;


$mate_post_layout_options = array(
    'global-layout' => esc_html__( 'Global Layout', 'mate' ),
    'layout-1' => esc_html__( 'Simple Layout', 'mate' ),
    'layout-2' => esc_html__( 'Banner Layout', 'mate' ),
);


/**
 * Callback function for post option.
*/
if( ! function_exists( 'mate_post_metafield_callback' ) ):
    
	function mate_post_metafield_callback() {
		global $post, $mate_post_layout_options;
        $post_type = get_post_type($post->ID);
		wp_nonce_field( basename( __FILE__ ), 'mate_post_meta_nonce' ); ?>
        
        <div class="metabox-main-block">

            <div class="metabox-navbar">
                <ul>

                    <li>
                        <a id="metabox-navbar-general" class="metabox-navbar-active" href="javascript:void(0)">

                            <?php esc_html_e('General Settings', 'mate'); ?>

                        </a>
                    </li>

                    <?php if( $post_type == 'post' ): ?>
                        <li>
                            <a id="metabox-navbar-appearance" href="javascript:void(0)">

                                <?php esc_html_e('Appearance Settings', 'mate'); ?>

                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if( $post_type == 'post' && class_exists('Booster_Extension_Class') ): ?>
                        <li>
                            <a id="twp-tab-booster" href="javascript:void(0)">

                                <?php esc_html_e('Booster Extension Settings', 'mate'); ?>

                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>

            <div class="twp-tab-content">

                <div id="metabox-navbar-general-content" class="metabox-content-wrap metabox-content-wrap-active">

                    <div class="metabox-opt-panel">

                        <h3 class="meta-opt-title"><?php esc_html_e('Sidebar Layout','mate'); ?></h3>

                        <div class="metabox-opt-wrap metabox-opt-wrap-alt">

                            <label><b><?php esc_html_e( 'Sidebar Layout','mate' ); ?></b></label>

                            <?php
                            $sidebar_layouts = mate_sidebar_layout();
                            $mate_post_sidebar = esc_html( get_post_meta( $post->ID, 'mate_post_sidebar_option', true ) ); 
                            if( $mate_post_sidebar == '' ){ $mate_post_sidebar = 'global-sidebar'; } ?>

                            <select name="mate_post_sidebar_option">
                                <option value="global-sidebar"><?php esc_html_e('Global Sidebar','mate'); ?></option>
                                <?php
                                foreach ( $sidebar_layouts as $key => $mate_post_sidebar_field ) { ?>
                                        <option <?php if( $mate_post_sidebar == $key ){ echo 'selected'; } ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $mate_post_sidebar_field );?></option>
                                <?php } ?>
                            </select>

                        </div>

                    </div>

                </div>

                <?php if( $post_type == 'post' ): ?>

                    <div id="metabox-navbar-appearance-content" class="metabox-content-wrap">

                        <div class="metabox-opt-panel">

                            <h3 class="meta-opt-title"><?php esc_html_e('Appearance Layout','mate'); ?></h3>

                            <div class="metabox-opt-wrap metabox-opt-wrap-alt">

                                <?php
                                $mate_post_layout = esc_html( get_post_meta( $post->ID, 'mate_post_layout', true ) ); 
                                if( $mate_post_layout == '' ){ $mate_post_layout = 'global-layout'; }

                                foreach ( $mate_post_layout_options as $key => $mate_post_layout_option) { ?>

                                    <label class="description">
                                        <input type="radio" name="mate_post_layout" value="<?php echo esc_attr( $key ); ?>" <?php if( $key == $mate_post_layout ){ echo "checked='checked'";} ?>/>&nbsp;<?php echo esc_html( $mate_post_layout_option ); ?>
                                    </label>

                                <?php } ?>

                            </div>

                        </div>

                        <div class="metabox-opt-panel">

                            <h3 class="meta-opt-title"><?php esc_html_e('Image Gradient Overlay Color','mate'); ?></h3>

                            <?php $twp_gradientcolor_type = esc_attr( get_post_meta($post->ID, 'twp_gradientcolor_type', true) ); ?>
                            <div class="metabox-opt-wrap metabox-opt-wrap-alt">

                                <label><b><?php esc_html_e( 'Gradient Overlay Color','mate' ); ?></b></label>

                                <select name="twp_gradientcolor_type">

                                    <option <?php if( $twp_gradientcolor_type == 'global' ){ echo 'selected'; } ?> value="global"><?php esc_html_e('Global','mate'); ?></option>
                                    <option <?php if( $twp_gradientcolor_type == '1' ){ echo 'selected'; } ?> value="1"><?php esc_html_e('Color Type 1','mate'); ?></option>
                                    <option <?php if( $twp_gradientcolor_type == '2' ){ echo 'selected'; } ?> value="2"><?php esc_html_e('Color Type 2','mate'); ?></option>
                                    <option <?php if( $twp_gradientcolor_type == '3' ){ echo 'selected'; } ?> value="3"><?php esc_html_e('Color Type 3','mate'); ?></option>
                                    <option <?php if( $twp_gradientcolor_type == '4' ){ echo 'selected'; } ?> value="4"><?php esc_html_e('Color Type 4','mate'); ?></option>

                                </select>

                            </div>
                        </div>

                        <div class="metabox-opt-panel">

                            <h3 class="meta-opt-title"><?php esc_html_e('Feature Image Setting','mate'); ?></h3>

                                <?php
                                $mate_ed_feature_image = esc_html( get_post_meta( $post->ID, 'mate_ed_feature_image', true ) ); ?>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-feature-image" name="mate_ed_feature_image" value="1" <?php if( $mate_ed_feature_image ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-feature-image"><?php esc_html_e( 'Disable Feature Image','mate' ); ?></label>

                            </div>

                        </div>

                         <div class="metabox-opt-panel">

                            <h3 class="meta-opt-title"><?php esc_html_e('Navigation Setting','mate'); ?></h3>

                            <?php $twp_disable_ajax_load_next_post = esc_attr( get_post_meta($post->ID, 'twp_disable_ajax_load_next_post', true) ); ?>
                            <div class="metabox-opt-wrap metabox-opt-wrap-alt">

                                <label><b><?php esc_html_e( 'Navigation Type','mate' ); ?></b></label>

                                <select name="twp_disable_ajax_load_next_post">

                                    <option <?php if( $twp_disable_ajax_load_next_post == '' || $twp_disable_ajax_load_next_post == 'global-layout' ){ echo 'selected'; } ?> value="global-layout"><?php esc_html_e('Global Layout','mate'); ?></option>
                                    <option <?php if( $twp_disable_ajax_load_next_post == 'no-navigation' ){ echo 'selected'; } ?> value="no-navigation"><?php esc_html_e('Disable Navigation','mate'); ?></option>
                                    <option <?php if( $twp_disable_ajax_load_next_post == 'norma-navigation' ){ echo 'selected'; } ?> value="norma-navigation"><?php esc_html_e('Next Previous Navigation','mate'); ?></option>
                                    <option <?php if( $twp_disable_ajax_load_next_post == 'ajax-next-post-load' ){ echo 'selected'; } ?> value="ajax-next-post-load"><?php esc_html_e('Ajax Load Next 3 Posts Contents','mate'); ?></option>

                                </select>

                            </div>
                        </div>

                    </div>

                <?php endif; ?>

                <?php if( $post_type == 'post' && class_exists('Booster_Extension_Class') ):

                    
                    $mate_ed_post_views = get_post_meta( $post->ID, 'mate_ed_post_views', true );
                    $mate_ed_post_read_time = get_post_meta( $post->ID, 'mate_ed_post_read_time', true );
                    $mate_ed_post_like_dislike = get_post_meta( $post->ID, 'mate_ed_post_like_dislike', true );
                    $mate_ed_post_author_box = get_post_meta( $post->ID, 'mate_ed_post_author_box', true );
                    $mate_ed_post_social_share = get_post_meta( $post->ID, 'mate_ed_post_social_share', true );
                    $mate_ed_post_reaction = get_post_meta( $post->ID, 'mate_ed_post_reaction', true );
                    $mate_ed_post_rating = get_post_meta( $post->ID, 'mate_ed_post_rating', true );
                    ?>

                    <div id="twp-tab-booster-content" class="metabox-content-wrap">

                        <div class="metabox-opt-panel">

                            <h3 class="meta-opt-title"><?php esc_html_e('Booster Extension Plugin Content','mate'); ?></h3>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-post-views" name="mate_ed_post_views" value="1" <?php if( $mate_ed_post_views ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-post-views"><?php esc_html_e( 'Disable Post Views','mate' ); ?></label>

                            </div>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-post-read-time" name="mate_ed_post_read_time" value="1" <?php if( $mate_ed_post_read_time ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-post-read-time"><?php esc_html_e( 'Disable Post Read Time','mate' ); ?></label>

                            </div>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-post-like-dislike" name="mate_ed_post_like_dislike" value="1" <?php if( $mate_ed_post_like_dislike ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-post-like-dislike"><?php esc_html_e( 'Disable Post Like Dislike','mate' ); ?></label>

                            </div>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-post-author-box" name="mate_ed_post_author_box" value="1" <?php if( $mate_ed_post_author_box ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-post-author-box"><?php esc_html_e( 'Disable Post Author Box','mate' ); ?></label>

                            </div>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-post-social-share" name="mate_ed_post_social_share" value="1" <?php if( $mate_ed_post_social_share ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-post-social-share"><?php esc_html_e( 'Disable Post Social Share','mate' ); ?></label>

                            </div>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-post-reaction" name="mate_ed_post_reaction" value="1" <?php if( $mate_ed_post_reaction ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-post-reaction"><?php esc_html_e( 'Disable Post Reaction','mate' ); ?></label>

                            </div>

                            <div class="metabox-opt-wrap twp-checkbox-wrap">

                                    <input type="checkbox" id="mate-ed-post-rating" name="mate_ed_post_rating" value="1" <?php if( $mate_ed_post_rating ){ echo "checked='checked'";} ?>/>
                                    <label for="mate-ed-post-rating"><?php esc_html_e( 'Disable Post Rating','mate' ); ?></label>

                            </div>

                        </div>

                    </div>

                <?php endif; ?>
                
            </div>

        </div>  
            
    <?php }
endif;

// Save metabox value.
add_action( 'save_post', 'mate_save_post_meta' );

if( ! function_exists( 'mate_save_post_meta' ) ):

    function mate_save_post_meta( $post_id ) {

        global $post, $mate_post_layout_options;

        if ( !isset( $_POST[ 'mate_post_meta_nonce' ] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mate_post_meta_nonce'] ) ), basename( __FILE__ ) ) ){

            return;

        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){

            return;

        }
            
        if ( 'page' == sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) {  

            if ( !current_user_can( 'edit_page', $post_id ) ){  

                return $post_id;

            }

        }elseif( !current_user_can( 'edit_post', $post_id ) ) {

            return $post_id;

        }

        $twp_disable_ajax_load_next_post_old = sanitize_text_field( get_post_meta( $post_id, 'twp_disable_ajax_load_next_post', true ) ); 
        $twp_disable_ajax_load_next_post_new = mate_sanitize_meta_pagination( wp_unslash( $_POST['twp_disable_ajax_load_next_post'] ) );
        if( $twp_disable_ajax_load_next_post_new && $twp_disable_ajax_load_next_post_new != $twp_disable_ajax_load_next_post_old ){

            update_post_meta ( $post_id, 'twp_disable_ajax_load_next_post', $twp_disable_ajax_load_next_post_new );

        }elseif( '' == $twp_disable_ajax_load_next_post_new && $twp_disable_ajax_load_next_post_old ) {

            delete_post_meta( $post_id,'twp_disable_ajax_load_next_post', $twp_disable_ajax_load_next_post_old );

        }

        foreach ( $mate_post_layout_options as $mate_post_layout_option ) {  
            
            $mate_post_layout_old = sanitize_text_field( get_post_meta( $post_id, 'mate_post_layout', true ) ); 
            $mate_post_layout_new = mate_sanitize_post_layout_option_meta( wp_unslash( $_POST['mate_post_layout'] ) );

            if ( $mate_post_layout_new && $mate_post_layout_new != $mate_post_layout_old ){

                update_post_meta ( $post_id, 'mate_post_layout', $mate_post_layout_new );

            }elseif( '' == $mate_post_layout_new && $mate_post_layout_old ) {

                delete_post_meta( $post_id,'mate_post_layout', $mate_post_layout_old );

            }
            
        }

        $mate_ed_feature_image_old = absint( get_post_meta( $post_id, 'mate_ed_feature_image', true ) ); 
        $mate_ed_feature_image_new = absint( wp_unslash( $_POST['mate_ed_feature_image'] ) );

        if ( $mate_ed_feature_image_new && $mate_ed_feature_image_new != $mate_ed_feature_image_old ){

            update_post_meta ( $post_id, 'mate_ed_feature_image', $mate_ed_feature_image_new );

        }elseif( '' == $mate_ed_feature_image_new && $mate_ed_feature_image_old ) {

            delete_post_meta( $post_id,'mate_ed_feature_image', $mate_ed_feature_image_old );

        }

        $twp_gradientcolor_type_old = absint( get_post_meta( $post_id, 'twp_gradientcolor_type', true ) ); 
        $twp_gradientcolor_type_new = absint( wp_unslash( $_POST['twp_gradientcolor_type'] ) );

        if ( $twp_gradientcolor_type_new && $twp_gradientcolor_type_new != $twp_gradientcolor_type_old ){

            update_post_meta ( $post_id, 'twp_gradientcolor_type', $twp_gradientcolor_type_new );

        }elseif( '' == $twp_gradientcolor_type_new && $twp_gradientcolor_type_old ) {

            delete_post_meta( $post_id,'twp_gradientcolor_type', $twp_gradientcolor_type_old );

        }

        $mate_ed_post_views_old = absint( get_post_meta( $post_id, 'mate_ed_post_views', true ) ); 
        $mate_ed_post_views_new = absint( wp_unslash( $_POST['mate_ed_post_views'] ) );

        if ( $mate_ed_post_views_new && $mate_ed_post_views_new != $mate_ed_post_views_old ){

            update_post_meta ( $post_id, 'mate_ed_post_views', $mate_ed_post_views_new );

        }elseif( '' == $mate_ed_post_views_new && $mate_ed_post_views_old ) {

            delete_post_meta( $post_id,'mate_ed_post_views', $mate_ed_post_views_old );

        }

        $mate_ed_post_read_time_old = absint( get_post_meta( $post_id, 'mate_ed_post_read_time', true ) ); 
        $mate_ed_post_read_time_new = absint( wp_unslash( $_POST['mate_ed_post_read_time'] ) );

        if ( $mate_ed_post_read_time_new && $mate_ed_post_read_time_new != $mate_ed_post_read_time_old ){

            update_post_meta ( $post_id, 'mate_ed_post_read_time', $mate_ed_post_read_time_new );

        }elseif( '' == $mate_ed_post_read_time_new && $mate_ed_post_read_time_old ) {

            delete_post_meta( $post_id,'mate_ed_post_read_time', $mate_ed_post_read_time_old );

        }

        $mate_ed_post_like_dislike_old = absint( get_post_meta( $post_id, 'mate_ed_post_like_dislike', true ) ); 
        $mate_ed_post_like_dislike_new = absint( wp_unslash( $_POST['mate_ed_post_like_dislike'] ) );

        if ( $mate_ed_post_like_dislike_new && $mate_ed_post_like_dislike_new != $mate_ed_post_like_dislike_old ){

            update_post_meta ( $post_id, 'mate_ed_post_like_dislike', $mate_ed_post_like_dislike_new );

        }elseif( '' == $mate_ed_post_like_dislike_new && $mate_ed_post_like_dislike_old ) {

            delete_post_meta( $post_id,'mate_ed_post_like_dislike', $mate_ed_post_like_dislike_old );

        }

        $mate_ed_post_author_box_old = absint( get_post_meta( $post_id, 'mate_ed_post_author_box', true ) ); 
        $mate_ed_post_author_box_new = absint( wp_unslash( $_POST['mate_ed_post_author_box'] ) );

        if ( $mate_ed_post_author_box_new && $mate_ed_post_author_box_new != $mate_ed_post_author_box_old ){

            update_post_meta ( $post_id, 'mate_ed_post_author_box', $mate_ed_post_author_box_new );

        }elseif( '' == $mate_ed_post_author_box_new && $mate_ed_post_author_box_old ) {

            delete_post_meta( $post_id,'mate_ed_post_author_box', $mate_ed_post_author_box_old );

        }

        $mate_ed_post_social_share_old = absint( get_post_meta( $post_id, 'mate_ed_post_social_share', true ) ); 
        $mate_ed_post_social_share_new = absint( wp_unslash( $_POST['mate_ed_post_social_share'] ) );

        if ( $mate_ed_post_social_share_new && $mate_ed_post_social_share_new != $mate_ed_post_social_share_old ){

            update_post_meta ( $post_id, 'mate_ed_post_social_share', $mate_ed_post_social_share_new );

        }elseif( '' == $mate_ed_post_social_share_new && $mate_ed_post_social_share_old ) {

            delete_post_meta( $post_id,'mate_ed_post_social_share', $mate_ed_post_social_share_old );

        }

        $mate_ed_post_reaction_old = absint( get_post_meta( $post_id, 'mate_ed_post_reaction', true ) ); 
        $mate_ed_post_reaction_new = absint( wp_unslash( $_POST['mate_ed_post_reaction'] ) );

        if ( $mate_ed_post_reaction_new && $mate_ed_post_reaction_new != $mate_ed_post_reaction_old ){

            update_post_meta ( $post_id, 'mate_ed_post_reaction', $mate_ed_post_reaction_new );

        }elseif( '' == $mate_ed_post_reaction_new && $mate_ed_post_reaction_old ) {

            delete_post_meta( $post_id,'mate_ed_post_reaction', $mate_ed_post_reaction_old );

        }

        $mate_ed_post_rating_old = absint( get_post_meta( $post_id, 'mate_ed_post_rating', true ) ); 
        $mate_ed_post_rating_new = absint( wp_unslash( $_POST['mate_ed_post_rating'] ) );

        if ( $mate_ed_post_rating_new && $mate_ed_post_rating_new != $mate_ed_post_rating_old ){

            update_post_meta ( $post_id, 'mate_ed_post_rating', $mate_ed_post_rating_new );

        }elseif( '' == $mate_ed_post_rating_new && $mate_ed_post_rating_old ) {

            delete_post_meta( $post_id,'mate_ed_post_rating', $mate_ed_post_rating_old );

        }

        $old = esc_html( get_post_meta( $post_id, 'mate_post_sidebar_option', true ) ); 
        $new = mate_sanitize_sidebar_option_meta( wp_unslash( $_POST['mate_post_sidebar_option'] ) );
        if ( $new && $new != $old ) {  
            update_post_meta ( $post_id, 'mate_post_sidebar_option', $new );  
        } elseif ( '' == $new && $old ) {  
            delete_post_meta( $post_id,'mate_post_sidebar_option', $old );  
        }

    }

endif;   