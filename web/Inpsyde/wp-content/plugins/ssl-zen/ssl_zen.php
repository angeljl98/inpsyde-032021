<?php

/**
 *
 * Helps install a FREE SSL certificate from LetsEncrypt, fixes mixed content, insecure content by redirecting to https, and forces SSL on all pages.
 *
 * Plugin Name:       SSL Zen - Free SSL Certificate & HTTPS Redirect for WordPress
 * Plugin URI:        https://sslzen.com
 * Description:       Helps install a free SSL certificate from LetsEncrypt, fixes mixed content, insecure content by redirecting to https, and forces SSL on all pages.
 * Version:           3.1.2
 * Author:            SSL Zen
 * Author URI:        http://sslzen.com
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ssl-zen
 * Domain Path:       ssl_zen/languages
 *
 * @author      SSL Zen
 * @category    Plugin
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 *
 */
/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
    die( 'Access Denied' );
}
/**
 * Require external package dependencies
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

if ( !function_exists( 'sz_fs' ) ) {
    // Create a helper function for easy SDK access.
    function sz_fs()
    {
        global  $sz_fs ;
        
        if ( !isset( $sz_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $sz_fs = fs_dynamic_init( array(
                'id'              => '4586',
                'slug'            => 'ssl-zen',
                'type'            => 'plugin',
                'public_key'      => 'pk_89da8f4d86d21701663c6381a4ab4',
                'is_premium'      => false,
                'premium_suffix'  => 'Pro',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'all',
                'menu'            => array(
                'pricing'    => false,
                'slug'       => 'ssl_zen',
                'first-path' => 'admin.php?page=ssl_zen&tab=step1',
            ),
                'is_live'         => true,
            ) );
        }
        
        return $sz_fs;
    }
    
    // Init Freemius.
    sz_fs();
    // Trying to customize the freemius message
    function sz_fs_custom_connect_message_on_update(
        $message,
        $user_first_name,
        $product_title,
        $user_login,
        $site_link,
        $freemius_link
    )
    {
        return sprintf(
            __( 'Hey %1$s', 'my-text-domain' ) . ',<br>' . __( 'We highly recommend that you opt-in to our security notifications. Opting in also helps us provide you fast support. We track non-sensitive diagnostic data using Freemius.', 'ssl-zen' ),
            $user_first_name,
            '<b>' . $product_title . '</b>',
            '<b>' . $user_login . '</b>',
            $site_link,
            $freemius_link
        );
    }
    
    sz_fs()->add_filter(
        'connect_message_on_update',
        'sz_fs_custom_connect_message_on_update',
        10,
        6
    );
    // Signal that SDK was initiated.
    do_action( 'sz_fs_loaded' );
}

/**
 * Define constants used in the plugin
 */
if ( !defined( 'SSL_ZEN_PLUGIN_VERSION' ) ) {
    define( 'SSL_ZEN_PLUGIN_VERSION', '3.1.2' );
}
if ( !defined( 'SSL_ZEN_DIR' ) ) {
    define( 'SSL_ZEN_DIR', plugin_dir_path( __FILE__ ) . 'ssl_zen/' );
}
if ( !defined( 'SSL_ZEN_URL' ) ) {
    define( 'SSL_ZEN_URL', plugin_dir_url( __FILE__ ) . 'ssl_zen/' );
}
if ( !defined( 'SSL_ZEN_BASEFILE' ) ) {
    define( 'SSL_ZEN_BASEFILE', plugin_basename( __FILE__ ) );
}
if ( !defined( 'SSL_ZEN_PLUGIN_ALLOW_DEV' ) ) {
    // to enable development on local environments.
    define( 'SSL_ZEN_PLUGIN_ALLOW_DEV', false );
}
if ( !defined( 'SSL_ZEN_PLUGIN_ALLOW_DEBUG' ) ) {
    // to enable debugging logs
    define( 'SSL_ZEN_PLUGIN_ALLOW_DEBUG', true );
}
if ( !defined( 'SSL_ZEN_PLUGIN_AUTH_HOST' ) ) {
    // the host of the auth plugin, with or without trailing slash.
    define( 'SSL_ZEN_PLUGIN_AUTH_HOST', 'https://api.sslzen.com' );
}
/**
 * Include the core file of the plugin
 */
require_once SSL_ZEN_DIR . 'classes/class.ssl_zen.php';
if ( !function_exists( 'ssl_zen_init' ) ) {
    /**
     * Function to initialize the plugin.
     *
     * @return class object
     */
    function ssl_zen_init()
    {
        /* Initialize the base class of the plugin */
        return ssl_zen::instance();
    }

}
/**
 * Create the main object of the plugin when the plugins are loaded
 */
add_action( 'plugins_loaded', 'ssl_zen_init' );