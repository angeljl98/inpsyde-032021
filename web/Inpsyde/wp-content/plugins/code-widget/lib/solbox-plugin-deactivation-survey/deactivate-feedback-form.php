<?php
namespace solbox_plugin_feedback;

if ( ! is_admin() ) {
	return;
}

global $pagenow;

if ( $pagenow != 'plugins.php' ) {
	return;
}

if ( defined( 'SB_CW_DEACTIVATE_FEEDBACK_FORM_INCLUDED' ) ) {
	return;
}
define( 'SB_CW_DEACTIVATE_FEEDBACK_FORM_INCLUDED', true );

add_action(
	'admin_enqueue_scripts',
	function() {

		// Enqueue scripts
		wp_enqueue_script( 'remodal', plugin_dir_url( __FILE__ ) . 'remodal.min.js' );
		wp_enqueue_style( 'remodal', plugin_dir_url( __FILE__ ) . 'remodal.css' );
		wp_enqueue_style( 'remodal-default-theme', plugin_dir_url( __FILE__ ) . 'remodal-default-theme.css' );

		wp_enqueue_script( 'solbox-deactivate-feedback-cw', plugin_dir_url( __FILE__ ) . 'deactivate-feedback-form.js' );
		wp_enqueue_style( 'solbox-deactivate-feedback-cw', plugin_dir_url( __FILE__ ) . 'deactivate-feedback-form.css' );

		// Localized strings
		wp_localize_script(
			'solbox-deactivate-feedback-cw',
			'solbox_deactivate_feedback_form_strings',
			array(
				'quick_feedback'        => __( 'Quick Feedback', 'solbox' ),
				'foreword'              => __( 'If you would be kind enough, please tell us why you\'re deactivating?', 'solbox' ),
				'better_plugins_name'   => __( 'Please tell us which plugin?', 'solbox' ),
				'please_tell_us'        => __( 'Please tell us the reason so we can improve the plugin', 'solbox' ),
				'do_not_attach_email'   => __( 'Do not send my e-mail address with this feedback', 'solbox' ),
				'brief_description'     => __( 'Please give us any feedback that could help us improve', 'solbox' ),
				'cancel'                => __( 'Cancel', 'solbox' ),
				'skip_and_deactivate'   => __( 'Skip &amp; Deactivate', 'solbox' ),
				'submit_and_deactivate' => __( 'Submit &amp; Deactivate', 'solbox' ),
				'please_wait'           => __( 'Please wait', 'solbox' ),
				'thank_you'             => __( 'Deactivating...', 'solbox' ),
			)
		);

		// Plugins
		// $plugins = apply_filters('solbox_deactivate_feedback_form_plugins', array());
		// $plugins   = [];
		$plugins[] = (object) [
			'slug'    => 'code-widget',
			'version' => CODE_WIDGET_VERSION,
		];

		// Reasons
		$defaultReasons = array(
			'suddenly-stopped-working' => __( 'The plugin suddenly stopped working', 'solbox' ),
			'plugin-broke-site'        => __( 'The plugin broke my site', 'solbox' ),
			'no-longer-needed'         => __( 'I don\'t need this plugin any more', 'solbox' ),
			'found-better-plugin'      => __( 'I found a better plugin', 'solbox' ),
			'share-plugin-name'        => __( 'Please share which plugin', 'solbox' ),
			'temporary-deactivation'   => __( 'It\'s a temporary deactivation, I\'m troubleshooting', 'solbox' ),
			'other'                    => __( 'Other', 'solbox' ),
		);

		foreach ( $plugins as $plugin ) {
			$plugin->reasons = apply_filters( 'solbox_deactivate_feedback_form_reasons', $defaultReasons, $plugin );
		}

		$plugin->security = wp_create_nonce( 'solbox-plugin-deactivate-nonce' );
		// Send plugin data
		wp_localize_script( 'solbox-deactivate-feedback-cw', 'solbox_deactivate_feedback_form_cw', $plugins );
	}
);

/**
 * Hook for adding plugins, pass an array of objects in the following format:
 *  'slug'      => 'plugin-slug'
 *  'version'   => 'plugin-version'
 *
 * @return array The plugins in the format described above
 */
add_filter(
	'solbox_deactivate_feedback_form_plugins',
	function( $plugins ) {
		return $plugins;
	}
);
