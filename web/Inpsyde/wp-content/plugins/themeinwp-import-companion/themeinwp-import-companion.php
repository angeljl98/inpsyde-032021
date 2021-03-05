<?php
/*
* Plugin Name: Themeinwp Import Companion
* Plugin URI: https://www.themeinwp.com/themeinwp-import-companion/
* Description: The plugin simply store data to import.
* Version: 1.0.5
* Author: ThemeInWP
* Author URI: https://www.themeinwp.com/
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tested up to: 5.6
* Requires PHP: 5.5
* Text Domain: themeinwp-import-companion
*/


// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$upload_dir = wp_upload_dir();

class Themeinwp_Import_Companion {

    private $before_import;

    public function __construct() {

        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

        $template  = get_option('template');
        $template_in = array('tribunal','infinity-mag','jumla','business-startup','knight','insights','dolpa','ecommerce-prime','telegram','mate');
        if( in_array( $template, $template_in ) ){

            add_filter( 'demo_import_kit_project_id', array( $this, 'import_files' ) );
            add_filter( 'demo_import_kit_primary_cat', array( $this, 'primary_category' ) );
            add_filter( 'demo_import_kit_secondary_cat', array( $this, 'secondary_category' ) );
            add_filter( 'demo_import_kit_import_files', array( $this, 'import_files' ) );
            add_filter( 'demo_import_kit_project_id', array( $this, 'project_id' ) );
            add_filter( 'demo_import_kit_upgrade_pro', array( $this, 'upgrade_pro' ) );
            add_action( 'plugins_loaded', array( $this, 'before_import' ) );
            add_action( 'dik/after_import', array( $this, 'after_import' ) );
        }

        add_action( 'plugins_loaded', array( $this, 'admin_notice' ) );
    }

    /**
     * Load the plugin textdomain.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'demo-import-kit', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
    }

    public function admin_notice() {
        
        if( !class_exists( 'Demo_Import_Kit_Class' ) ){

            if( is_multisite() ){

              add_action( 'network_admin_notices',array( $this,'admin_notiece_render' ) );

            } else {

              add_action( 'admin_notices',array( $this,'admin_notiece_render' ) );
            }
        }

    }

    public static function admin_notiece_render(){

            ?>
        <div class="updated notice is-dismissible">

            <h3><?php esc_html_e('Themeinwp Import Companion','themeinwp-import-companion'); ?></h3>

            <strong><p><?php esc_html_e('Please install Demo Import Kit Plugin.','themeinwp-import-companion'); ?></p></strong>

        </div>

    <?php
    }

    public function upgrade_pro() {

        if( $this->before_import && isset( $this->before_import->upgrade_pro ) && $this->before_import->upgrade_pro ){
            return  $upgrade_pro = $this->before_import->upgrade_pro;
        }
        return false;

    }
    
    public function project_id() {

        return  $project_id = '20724620';

    }

    public function before_import() {

        $template   = get_template();
        $before_import = wp_remote_retrieve_body( wp_remote_get('https://gitlab.com/api/v4/projects/20724620/repository/files/'.esc_attr( $template ).'%2Fdemo-content%2Ejson/?ref=master' ) );

        $before_import      = json_decode( $before_import,true );
        $before_import  = $before_import['content'];
        $before_import    = base64_decode( $before_import );

        $before_import = base64_decode($before_import);
        $before_import = json_decode($before_import);
        
        $this->before_import = $before_import;

    }

    public function primary_category() {

        if( $this->before_import && isset( $this->before_import->primary_category ) && $this->before_import->primary_category ){
            return  $secondary_cat_array = $this->before_import->primary_category;
        }
        return false;
    }

    public function secondary_category() {

            if( $this->before_import && isset( $this->before_import->secondary_category ) && $this->before_import->secondary_category ){
                return  $secondary_cat_array = $this->before_import->secondary_category;
            }
            return false;
        }

    public function import_files() {

        if( $this->before_import && isset( $this->before_import->import_files ) && $this->before_import->import_files ){
                return  $secondary_cat_array = $this->before_import->import_files;
            }
            return false;   
    }


    function after_import( ) {

        $template   = get_template();
        $after_import = wp_remote_retrieve_body( wp_remote_get('https://gitlab.com/api/v4/projects/20724620/repository/files/'.esc_attr( $template ).'%2Fafter-import%2Ejson/?ref=master' ) );

        $after_import      = json_decode( $after_import,true );

        if( isset( $after_import['content'] ) ){

            $after_import  = $after_import['content'];
            $after_import    = base64_decode( $after_import );
            
            $after_import = base64_decode($after_import);
            $after_import = json_decode($after_import);

            if( isset( $after_import->menus ) ){
                
                $menu_array = array();

                foreach( $after_import->menus as $key => $value ){

                    $menu   = get_term_by('name', $value, 'nav_menu');
                    if( isset( $menu->term_id ) ){
                        $menu_array[$key] = $menu->term_id;
                    }

                }

                set_theme_mod( 'nav_menu_locations' , 
                    $menu_array
                );
            }

            
            if( isset( $after_import->home ) && $after_import->home ){
                $front_page_id = get_page_by_title( $after_import->home );
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', $front_page_id->ID );
            }
        }
    }

}

$GLOBALS[ 'themeinwp_import_companion_global' ] = new Themeinwp_Import_Companion();