<?php
/*
* Plugin Name: Demo Import Kit
* Plugin URI: https://www.themeinwp.com/demo-import-kit/
* Description: The plugin simpally import and export customizer, Widget and content data.
* Version: 1.0.5
* Author: ThemeInWP
* Author URI: https://www.themeinwp.com/
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tested up to: 5.6
* Requires PHP: 5.5
* Text Domain: demo-import-kit
*/


// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'DIK_PATH', plugin_dir_path( __FILE__ ) );

// Path/URL to root of this plugin, with trailing slash.
define( 'DIK_URL', plugin_dir_url( __FILE__ ) );

// Base
require DIK_PATH . 'inc/base.php';

$upload_dir = wp_upload_dir();
$demo_import_kit_temp_folder =  $upload_dir['basedir'] . '/demo-import-kit-temp/';

define( 'DEMO_IMPORT_KIT_FOLDER', $demo_import_kit_temp_folder );

class Demo_Import_Kit_Class {

	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		require DIK_PATH . 'inc/importer.php';
		require DIK_PATH . 'inc/widget-importer.php';
		require DIK_PATH . 'inc/customizer-importer.php';
		require DIK_PATH . 'inc/logger.php';

	}

	/**
	 * Enqueue style and scripts
	 */
	public function admin_enqueue_scripts( $hook ) {

		wp_enqueue_style( 'demo-import-kit-style', DIK_URL . 'assets/css/style.css', array() , '' );
		wp_enqueue_script( 'imagesloaded' );
        wp_enqueue_script( 'isotope-pkgd', DIK_URL . 'assets/js/isotope.pkgd.min.js' , array( 'jquery' ), '' );
		wp_enqueue_script( 'demo-import-kit-script', DIK_URL . 'assets/js/script.js' , array( 'jquery', 'jquery-form' ), '' );

		wp_localize_script( 'demo-import-kit-script', 'dik',
			array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'   => wp_create_nonce( 'demo-import-kit-ajax-verification' ),
                'importing_title'   => esc_html__( 'Importing Demo Content','demo-import-kit'),
                'importing_message'   => esc_html__( 'Please do not Reload or close windows it will take a while to import demo.','demo-import-kit'),
                'import_status'   => esc_html__( 'Status','demo-import-kit'),
                'required_file'   => esc_html__( 'File Is Required','demo-import-kit'),
			)
		);

	}

	/**
	 * Load the plugin textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'demo-import-kit', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

}

$GLOBALS[ 'demo_import_kit_global' ] = new Demo_Import_Kit_Class();