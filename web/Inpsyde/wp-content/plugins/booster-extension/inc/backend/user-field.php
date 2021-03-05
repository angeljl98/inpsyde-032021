<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * User Profile Extra Field.
 *
 * @package Booster Extension
**/

add_action( 'show_user_profile', 'booster_extension_user_profile_fields' );
add_action( 'edit_user_profile', 'booster_extension_user_profile_fields' );

function booster_extension_user_profile_fields( $user ) {

	$be_user_avatar = get_the_author_meta( 'be_user_avatar', $user->ID );
    $be_user_background_avatar = get_the_author_meta( 'be_user_background_avatar', $user->ID ); ?>

    <h3><?php esc_html_e("Miscellaneous Options", "booster-extension"); ?></h3>

    <table class="form-table">

	    <tr>
	        <th><label for="be_user_avatar"><?php esc_html_e("User Display Picture",'booster-extension'); ?></label></th>

	        <td>

	            <div class="twp-img-fields-wrap">
                    <div class="attachment-media-view">
                        <div class="twp-img-fields-wrap">
                            <div class="twp-attachment-media-view">

                                <div class="twp-attachment-child twp-uploader">

                                    <button type="button" class="be-img-upload-button">
                                        <span class="dashicons dashicons-upload twp-icon twp-icon-large"></span>
                                    </button>

                                    <input class="upload-id" name="be_user_avatar" type="hidden" value="<?php echo esc_url( $be_user_avatar ); ?>"/>

                                </div>

                                <div class="twp-attachment-child twp-thumbnail-image">

                                    <button type="button" class="twp-img-delete-button <?php if( $be_user_avatar ) { echo 'twp-img-show'; } ?>">
                                        <span class="dashicons dashicons-no-alt twp-icon"></span>
                                    </button>

                                    <div class="twp-img-container">

                                        <?php if( $be_user_avatar ){ ?>

                                            <img src="<?php echo esc_url( $be_user_avatar ); ?>" style="width:200px;height:auto;">

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

	        </td>

	    </tr>

        <tr>
            <th><label for="be_user_background_avatar"><?php esc_html_e("User Background Picture",'booster-extension'); ?></label></th>

            <td>

                <div class="twp-img-fields-wrap">
                    <div class="attachment-media-view">
                        <div class="twp-img-fields-wrap">
                            <div class="twp-attachment-media-view">

                                <div class="twp-attachment-child twp-uploader">

                                    <button type="button" class="be-img-upload-button">
                                        <span class="dashicons dashicons-upload twp-icon twp-icon-large"></span>
                                    </button>

                                    <input class="upload-id" name="be_user_background_avatar" type="hidden" value="<?php echo esc_url( $be_user_background_avatar ); ?>"/>

                                </div>

                                <div class="twp-attachment-child twp-thumbnail-image">

                                    <button type="button" class="twp-img-delete-button <?php if( $be_user_background_avatar ) { echo 'twp-img-show'; } ?>">
                                        <span class="dashicons dashicons-no-alt twp-icon"></span>
                                    </button>

                                    <div class="twp-img-container">

                                        <?php if( $be_user_background_avatar ){ ?>

                                            <img src="<?php echo esc_url( $be_user_background_avatar ); ?>" style="width:200px;height:auto;">

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </td>

        </tr>

    </table>

<?php }

add_action( 'personal_options_update', 'booster_extension_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'booster_extension_save_user_profile_fields' );

function booster_extension_save_user_profile_fields( $user_id ) {

    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }

    update_user_meta( $user_id, 'be_user_avatar', $_POST['be_user_avatar'] );
    update_user_meta( $user_id, 'be_user_background_avatar', $_POST['be_user_background_avatar'] );

}