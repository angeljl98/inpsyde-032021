<?php

/**
 *
 * Helps install a free SSL certificate from LetsEncrypt, fixes mixed content, insecure content by redirecting to https, and forces SSL on all pages.
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * Plugin Name:       Free SSL Certificate & HTTPS Redirector for WordPress - SSL Zen
 * Plugin URI:        https://sslzen.com
 * Description:       Helps install a free SSL certificate from LetsEncrypt, fixes mixed content, insecure content by redirecting to https, and forces SSL on all pages.
 * Version:           1.9.6
 * Author:            SSL
 * Author URI:        http://sslzen.com
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ssl-zen
 * Domain Path:       ssl_zen/languages
 *
 * @author      SSL
 * @category    Plugin
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

if ( !class_exists( 'ssl_zen_admin' ) ) {
    /**
     * Class to manage the admin settings of ssl_zen
     */
    class ssl_zen_admin
    {
        /**
         * Add hooks and filters for admin pages
         *
         * @since 1.0
         * @static
         */
        public static function init()
        {
            register_deactivation_hook( SSL_ZEN_BASEFILE, __CLASS__ . '::deactivate_plugin' );
            // Manage admin menu
            add_action( 'admin_menu', __CLASS__ . '::admin_menu' );
            // Linked menu for restart setup
            add_action( 'admin_menu', __CLASS__ . '::ssl_zen_admin_menu_linked' );
            add_action( 'admin_init', __CLASS__ . '::admin_init', 12 );
            add_action( 'plugin_action_links_' . SSL_ZEN_BASEFILE, __CLASS__ . '::plugin_action_links' );
            // Domain verification ajax hook
            add_action( 'wp_ajax_ssl_zen_domain_verification', __CLASS__ . '::ssl_zen_domain_verification' );
            // WWW sub domain checker ajax hook
            add_action( 'wp_ajax_ssl_zen_check_for_dns_records', __CLASS__ . '::ssl_zen_check_for_dns_records' );
            // Cert files ajax hook
            add_action( 'wp_ajax_ssl_zen_cert_files', __CLASS__ . '::ssl_zen_cert_files' );
            // Enable log debugging mode
            add_action( 'wp_ajax_ssl_zen_settings_debug', __CLASS__ . '::ssl_zen_settings_debug' );
            // These actions should be available for both free and premium
            // Ajax for validate input fields on step 1 cpanel username and password
            add_action( 'wp_ajax_ssl_zen_cpanel_check_credentials_ajax', __CLASS__ . '::ssl_zen_cpanel_check_credentials_ajax' );
            add_action( 'wp_ajax_nopriv_ssl_zen_cpanel_check_credentials_ajax', __CLASS__ . '::ssl_zen_cpanel_check_credentials_ajax' );
            self::review_notice();
        }
        
        /**
         * Called when a stackpath license is deactivated and a free plan becomes active.
         */
        public static function stackpath_downgrade()
        {
        }
        
        /**
         * Single-entry ajax method.
         */
        public static function ajax_stackpath()
        {
            check_ajax_referer( 'ssl_zen_ajax', 'security' );
            $success = $error = array();
            switch ( $_POST['_action'] ) {
                case 'step2':
                    $apiResponse = ssl_zen_auth::call( 'verify_records' );
                    $correct_records = ( $apiResponse ? $apiResponse['correct_records'] : array() );
                    
                    if ( !$apiResponse || intval( $apiResponse['wait'] ) === 1 ) {
                        $error = array(
                            'notice'  => sprintf( '<div class="message warning sslzen-nowrap">%s</div>', $apiResponse['wait_reason'] ),
                            'records' => $correct_records,
                        );
                    } else {
                        $success = array(
                            'notice'  => sprintf( '<div class="message success">%s</div>', __( 'You have successfully pointed the records to Stackpath.', 'ssl-zen' ) ),
                            'records' => $correct_records,
                        );
                        update_option( 'ssl_zen_settings_stage', 'step3' );
                    }
                    
                    break;
                case 'step3':
                    $apiResponse = ssl_zen_auth::call( 'request_ssl' );
                    switch ( $apiResponse['status'] ) {
                        case 'ACTIVE':
                            update_option( 'ssl_zen_settings_stage', 'step4' );
                            update_option( 'ssl_zen_cert_details', $apiResponse['details'] );
                            $success = array(
                                'notice' => sprintf( '<div class="message success">%s</div>', __( 'You have successfully generated a free SSL certificate for your website.', 'ssl-zen' ) ),
                            );
                            break;
                        default:
                            $error = array(
                                'notice' => '',
                            );
                            // this can be empty as the notice is already displayed.
                    }
                    break;
            }
            if ( $error ) {
                wp_send_json_error( $error );
            }
            if ( $success ) {
                wp_send_json_success( $success );
            }
        }
        
        /**
         * All the stackpath related hooks, when a license is active.
         */
        public static function stackpath_hooks()
        {
        }
        
        /**
         * Deactivate stackpath.
         */
        public static function remove_stackpath( $check_plan = true )
        {
        }
        
        /**
         * Purge url for a specific post.
         */
        public static function purge_url( $postID, WP_Post $post, $update )
        {
        }
        
        /**
         * Update stackpath-related settings and fire any API calls.
         */
        private static function updateStackpathSettings()
        {
        }
        
        /**
         * Get the path for wp-config.php.
         */
        public static function get_wp_config()
        {
            $wp_config_path = null;
            return $wp_config_path;
        }
        
        /**
         * Removes the changes made in wp-config.php for stackpath.
         */
        public static function remove_fix_wp_config()
        {
        }
        
        /**
         * Determines if wp-config.php contains changes for stackpath.
         */
        public static function wp_config_has_stackpath_changes()
        {
            return false;
        }
        
        /**
         * Make changes to wp-config.php for stackpath.
         */
        public static function fix_wp_config()
        {
        }
        
        /**
         * to manage the allowed tabs after each step
         *
         * @var $allowedTabs
         * @since 1.0
         */
        private static  $allowedTabs = array(
            ''                          => array( '' ),
            'cloudflare_detected_state' => array(
            '',
            'cloudflare_detected_state',
            'layout' => array(
            'steps_nav' => false,
            'footer'    => false,
        ),
            'method' => 'cloudflareDetectedState'
        ),
            'bluehost_detected_state'   => array(
            '',
            'bluehost_detected_state',
            'layout' => array(
            'steps_nav' => false,
            'footer'    => false,
        ),
            'method' => 'bluehostDetectedState'
        ),
            'error_state'               => array(
            '',
            'error_state',
            'layout' => array(
            'steps_nav' => false,
            'footer'    => false,
        ),
            'method' => 'errorState'
        ),
            'system_requirements'       => array(
            '',
            'system_requirements',
            'upgrade',
            'support',
            'layout' => array(
            'steps_nav' => false,
            'footer'    => false,
        ),
            'method' => 'systemRequirements'
        ),
            'pricing'                   => array(
            '',
            'pricing',
            'layout' => array(
            'steps_nav' => false,
            'footer'    => false,
        ),
            'method' => 'pricing'
        ),
            'step1'                     => array(
            '',
            'step1',
            'settings',
            'system_requirements',
            'pricing',
            'upgrade',
            'support',
            'layout' => array(
            'steps_nav' => true,
            'footer'    => true,
        ),
            'method' => 'step1'
        ),
            'step2'                     => array(
            '',
            'step2',
            'step1',
            'settings',
            'system_requirements',
            'pricing',
            'upgrade',
            'support',
            'layout' => array(
            'steps_nav' => true,
            'footer'    => true,
        ),
            'method' => 'step2'
        ),
            'step3'                     => array(
            '',
            'step3',
            'step1',
            'settings',
            'system_requirements',
            'pricing',
            'upgrade',
            'support',
            'layout' => array(
            'steps_nav' => true,
            'footer'    => true,
        ),
            'method' => 'step3'
        ),
            'step4'                     => array(
            '',
            'step4',
            'step1',
            'settings',
            'system_requirements',
            'pricing',
            'upgrade',
            'support',
            'layout' => array(
            'steps_nav' => true,
            'footer'    => true,
        ),
            'method' => 'step4'
        ),
            'review'                    => array(
            '',
            'review',
            'step1',
            'system_requirements',
            'pricing',
            'settings',
            'settings.advanced',
            'upgrade',
            'support',
            'layout' => array(
            'steps_nav' => false,
            'footer'    => false,
        ),
            'method' => 'review'
        ),
            'settings'                  => array(
            '',
            'settings',
            'settings.advanced',
            'system_requirements',
            'pricing',
            'review',
            'upgrade',
            'support',
            'layout' => array(
            'steps_nav' => false,
            'footer'    => true,
        ),
            'method' => 'settings'
        ),
        ) ;
        /**
         * Ajax Method for domain verification scan
         */
        public static function ssl_zen_domain_verification()
        {
            // Initialize variables
            $nonce = ( isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '' );
            $result = [];
            $result['status'] = 0;
            
            if ( wp_verify_nonce( $nonce, 'ssl_zen_verify' ) ) {
                $variant = ( isset( $_REQUEST['variant'] ) ? sanitize_text_field( $_REQUEST['variant'] ) : '' );
                
                if ( !empty($variant) ) {
                    $leVariantType = ( $variant == 'http' ? \LEClient\LEOrder::CHALLENGE_TYPE_HTTP : \LEClient\LEOrder::CHALLENGE_TYPE_DNS );
                    // Update selected verification variant ( The other one maybe be failed initially )
                    update_option( 'ssl_zen_domain_verification_variant', $variant );
                    // Check all the pending authorizations and update validation status
                    ssl_zen_certificate::updateAuthorizations( $leVariantType, false );
                    // Check if all authorizations are valid
                    $isValid = ssl_zen_certificate::validateAuthorization( false );
                    // If verification succeeded, then store the flag
                    
                    if ( $isValid ) {
                        update_option( 'ssl_zen_domain_verified', '1' );
                        // Remove http verification files, no meter what variant have used before
                        ssl_zen_helper::deleteAll( ABSPATH . '.well-known/acme-challenge', true );
                        $result['message'] = __( 'Successfully verified', 'ssl-zen' );
                    } else {
                        // If not succeeded and variant was DNS then store now+300 sec for next time to allow to check
                        
                        if ( $variant == 'dns' ) {
                            $fiveMinutes = 300;
                            update_option( 'ssl_zen_dns_check_activation', time() + $fiveMinutes );
                            $result['time'] = $fiveMinutes;
                            $result['message'] = __( 'We couldn\'t find your verification token in your domain\'s TXT records.', 'ssl-zen' ) . __( 'Please try again in 5 minutes', 'ssl-zen' ) . ' ' . __( 'or try http variant.', 'ssl-zen' );
                        } else {
                            $result['message'] = __( 'Verification failed, try dns variant.', 'ssl-zen' );
                        }
                    
                    }
                    
                    $result['status'] = $isValid;
                } else {
                    $result['message'] = __( 'Invalid verification variant', 'ssl-zen' );
                }
            
            } else {
                $result['message'] = __( 'Invalid nonce.Please refresh the page.', 'ssl-zen' );
            }
            
            print_r( json_encode( $result ) );
            wp_die();
        }
        
        /**
         * Ajax call handler for www sub domain checker
         */
        public static function ssl_zen_check_for_dns_records()
        {
            // Initialize variables
            $nonce = ( isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '' );
            $input = ( isset( $_POST['domain'] ) ? sanitize_text_field( $_POST['domain'] ) : '' );
            $ip = $_SERVER['SERVER_ADDR'];
            $result = [];
            $result['status'] = 0;
            $result['message'] = sprintf(
                /* translators: 1: Input 2: IP Address*/
                __( 'www.%1$s is not pointed to %2$s. Please create "A" or "CNAME" record for www sub-domain or uncheck the include www option', 'ssl-zen' ),
                $input,
                $ip
            );
            
            if ( wp_verify_nonce( $nonce, 'ssl_zen_generate_certificate' ) ) {
                
                if ( !empty($input) ) {
                    $input = trim( $input, '/' );
                    if ( !preg_match( '#^http(s)?://#', $input ) ) {
                        $input = 'http://' . $input;
                    }
                    $urlParts = parse_url( $input );
                    $domain = preg_replace( '/^www\\./', '', $urlParts['host'] );
                    $subDomain = 'www.' . $domain;
                    $data = dns_get_record( $subDomain );
                    if ( !empty($data) ) {
                        foreach ( $data as $item ) {
                            
                            if ( $item['type'] == 'A' && !empty($item['ip']) && $item['ip'] == $ip || $item['type'] == 'CNAME' && !empty($item['target']) && $item['target'] == $domain ) {
                                $result['status'] = 1;
                                unset( $result['message'] );
                                break;
                            }
                        
                        }
                    }
                } else {
                    $result['message'] = __( 'Domain is empty', 'ssl-zen' );
                }
            
            } else {
                $result['message'] = __( 'Invalid nonce.Please refresh the page.', 'ssl-zen' );
            }
            
            print_r( json_encode( $result ) );
            wp_die();
        }
        
        /**
         * Ajax call handler for showing cert file
         */
        public static function ssl_zen_cert_files()
        {
            $nonce = ( isset( $_GET['nonce'] ) ? sanitize_text_field( $_GET['nonce'] ) : '' );
            
            if ( wp_verify_nonce( $nonce, 'ssl_zen_install_certificate' ) ) {
                $fileName = ( isset( $_REQUEST['file_name'] ) ? sanitize_text_field( $_REQUEST['file_name'] ) : '' );
                
                if ( file_exists( SSL_ZEN_DIR . 'keys/' . $fileName ) ) {
                    $fileContent = file_get_contents( SSL_ZEN_DIR . 'keys/' . $fileName );
                    $result = [
                        'status' => 1,
                        'file'   => $fileContent,
                    ];
                } else {
                    $result = [
                        'status'  => 0,
                        'message' => __( 'Invalid file.', 'ssl-zen' ),
                    ];
                }
            
            } else {
                $result = [
                    'status'  => 0,
                    'message' => __( 'Invalid nonce.Please refresh the page.', 'ssl-zen' ),
                ];
            }
            
            print_r( json_encode( $result ) );
            wp_die();
        }
        
        /**
         * Ajax call handler for enabling log debug mode
         */
        public static function ssl_zen_settings_debug()
        {
            $nonce = ( isset( $_GET['nonce'] ) ? sanitize_text_field( $_GET['nonce'] ) : '' );
            
            if ( wp_verify_nonce( $nonce, 'ssl_zen_settings' ) ) {
                $enableDebug = ( isset( $_GET['enable_debug'] ) ? $_GET['enable_debug'] : 0 );
                
                if ( sz_fs()->is_plan( 'cdn', true ) ) {
                    $success = array(
                        'notice' => '',
                    );
                    
                    if ( $enableDebug ) {
                        $url = ssl_zen_helper::exposeLogAsFile();
                        $success = array(
                            'notice' => sprintf(
                            '<div class="message success">%s <i class="copy-clipboard" title="%s" data-clipboard-text="%s"></i></div><div class="message-container"></div>',
                            $url,
                            __( 'Copy', 'ssl-zen' ),
                            $url
                        ),
                        );
                        update_option( 'ssl_zen_show_debug_url', $enableDebug );
                        update_option( 'ssl_zen_debug_url', $url );
                    } else {
                        ssl_zen_helper::removeLogs();
                        delete_option( 'ssl_zen_show_debug_url' );
                        delete_option( 'ssl_zen_debug_url' );
                    }
                    
                    wp_send_json_success( $success );
                } else {
                    $status = update_option( 'ssl_zen_enable_debug', $enableDebug );
                    $result = [
                        'status' => $status,
                    ];
                }
            
            } else {
                $result = [
                    'status'  => 0,
                    'message' => __( 'Invalid nonce.Please refresh the page.', 'ssl-zen' ),
                ];
            }
            
            print_r( json_encode( $result ) );
            wp_die();
        }
        
        /**
         * Hook to manage the admin menu
         *
         * @since 1.0
         * @static
         */
        public static function admin_menu()
        {
            add_menu_page(
                __( 'SSL Zen', 'ssl-zen' ),
                __( 'SSL Zen', 'ssl-zen' ),
                'manage_options',
                'ssl_zen',
                __CLASS__ . '::ssl_zen_hook',
                'dashicons-lock',
                101
            );
            if ( sz_fs()->is_plan( 'pro', true ) ) {
                add_submenu_page(
                    'ssl_zen',
                    __( 'Setup', 'ssl-zen' ),
                    __( 'Setup', 'ssl-zen' ),
                    'manage_options',
                    'ssl_zen-restart-setup'
                );
            }
        }
        
        /**
         * Hook to manage the linked admin menu
         *
         * @since 1.13
         * @static
         */
        public static function ssl_zen_admin_menu_linked()
        {
            global  $submenu ;
            if ( sz_fs()->is_plan( 'pro', true ) ) {
                $submenu['ssl_zen'][1][2] = 'admin.php?page=ssl_zen&tab=step1';
            }
        }
        
        /**
         * Hook to validate input fields on step 1 cpanel username and password
         * Validating cpanel username and password API is available on both free and paid
         * @since 1.2
         * @static
         */
        public static function ssl_zen_cpanel_check_credentials_ajax()
        {
            
            if ( isset( $_POST ) ) {
                $res = self::verify_cpanel_cred( $_POST['username'], $_POST['password'], true );
                
                if ( $res === false ) {
                    echo  'false' ;
                } else {
                    echo  'true' ;
                }
            
            } else {
                echo  'false' ;
            }
            
            wp_die();
        }
        
        /**
         * Hook to display SSL Zen Settings page
         *
         * @since 1.0
         * @static
         */
        public static function ssl_zen_hook()
        {
            $tab = ( isset( $_REQUEST['tab'] ) ? trim( sanitize_text_field( $_REQUEST['tab'] ) ) : '' );
            ?>
            <div class="ssl-zen-content-container <?php 
            echo  ( $tab == 'review' ? 'review-page' : '' ) ;
            ?>">
                <header class="header clearfix">
                    <div class="container">
                        <div class="row align-items-center ">
                            <div class="col-lg-6 text-lg-left text-center logo mb-3 mb-lg-0">
                                <img src="<?php 
            echo  SSL_ZEN_URL ;
            ?>img/logo.svg"
                                     alt="">
                                <span>V<?php 
            echo  SSL_ZEN_PLUGIN_VERSION ;
            ?></span>
                                <span><?php 
            echo  ( sz_fs()->can_use_premium_code__premium_only() ? 'Premium' : ' Free' ) ;
            ?></span>
                            </div>
                            <div class="col-lg-6 text-lg-right text-center external-actions-container">
                                <?php 
            $stage = get_option( 'ssl_zen_settings_stage', '' );
            // show settings button only when the stage is that.
            
            if ( $stage === 'settings' && ssl_zen_helper::isTabAvailableAtThisStage( $tab, 'settings', self::$allowedTabs ) ) {
                ?>
                                    <a class="settings"
                                       href="<?php 
                echo  admin_url( 'admin.php?page=ssl_zen&tab=settings' ) ;
                ?>">
                                        <?php 
                _e( 'Settings', 'ssl-zen' );
                ?>
                                    </a>
                                <?php 
            }
            
            
            if ( ssl_zen_helper::isTabAvailableAtThisStage( $tab, 'upgrade', self::$allowedTabs ) && ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite() ) {
                ?>
                                    <a class="upgrade"
                                       href="https://checkout.freemius.com/mode/dialog/plugin/4586/plan/7397/licenses/1/">
                                        <?php 
                _e( 'Upgrade', 'ssl-zen' );
                ?>
                                    </a>
                                <?php 
            }
            
            
            if ( $stage !== 'settings' ) {
                ?>
                                    <a class="settings"
                                       href="<?php 
                echo  admin_url( 'admin.php?page=ssl_zen&tab=settings' ) ;
                ?>">
                                        <?php 
                _e( 'Debug', 'ssl-zen' );
                ?>
                                    </a>
                                <?php 
            }
            
            
            if ( ssl_zen_helper::isTabAvailableAtThisStage( $tab, 'support', self::$allowedTabs ) ) {
                ?>
                                    <a class="support"
                                       href="<?php 
                echo  admin_url( 'admin.php?page=ssl_zen-contact' ) ;
                ?>">
                                        <?php 
                _e( 'Support', 'ssl-zen' );
                ?>
                                    </a>
                                <?php 
            }
            
            ?>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="container mt-5">
                    <?php 
            // Check weather to show steps navigation
            if ( ssl_zen_helper::showLayoutPart( $tab, self::$allowedTabs, 'steps_nav' ) ) {
                self::stepsNavigation( $tab );
            }
            // Show message container
            self::showMessage();
            ?>
                    <section class="ssl-zen-container">
                        <?php 
            $tabMethod = ( isset( self::$allowedTabs[$tab]['method'] ) ? self::$allowedTabs[$tab]['method'] : '' );
            
            if ( method_exists( ssl_zen_admin::class, $tabMethod ) ) {
                self::$tabMethod();
            } else {
                $tabMethod = self::$allowedTabs[get_option( 'ssl_zen_settings_stage', 'system_requirements' )]['method'];
                self::$tabMethod();
            }
            
            ?>
                    </section>
                </div>
                <?php 
            
            if ( ssl_zen_helper::showLayoutPart( $tab, self::$allowedTabs, 'footer' ) && !sz_fs()->is_premium() ) {
                $upgradeUrl = add_query_arg( array(
                    'checkout'      => 'true',
                    'plan_id'       => 10884,
                    'plan_name'     => 'cdn',
                    'billing_cycle' => 'annual',
                    'pricing_id'    => 11089,
                    'currency'      => 'usd',
                ), sz_fs()->get_upgrade_url() );
                if ( ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite() ) {
                    $upgradeUrl = add_query_arg( array(
                        'checkout'      => 'true',
                        'plan_id'       => 7397,
                        'plan_name'     => 'pro',
                        'billing_cycle' => 'annual',
                        'pricing_id'    => 7115,
                        'currency'      => 'usd',
                    ), sz_fs()->get_upgrade_url() );
                }
                ?>
                    <footer class="ssl-zen-footer container">
                        <a href="<?php 
                echo  $upgradeUrl ;
                ?>">
                            <div class="row align-items-center">
                                <div class="col-lg-3 text-center text-lg-left ssl-zen-pro-quote">
                                    <h4>
                                        <?php 
                _e( 'Never Pay for SSL Again!', 'ssl-zen' );
                ?>
                                    </h4>
                                    <p class="mt-1">
                                        <?php 
                _e( 'Upgrade to our Pro Plan', 'ssl-zen' );
                ?>
                                    </p>
                                </div>
                                <div class="col-lg-7 ssl-zen-pro-features mt-4 mt-lg-0">
                                <span>
                                    <?php 
                _e( 'AUTOMATIC', 'ssl-zen' );
                ?><br>
	                                <?php 
                _e( 'DOMAIN VERIFICATION', 'ssl-zen' );
                ?>
                                </span>
                                    <span>
                                    <?php 
                _e( 'AUTOMATIC SSL INSTALLATION', 'ssl-zen' );
                ?>
                                </span>
                                    <span>
                                    <?php 
                _e( 'AUTOMATIC SSL RENEWAL', 'ssl-zen' );
                ?>
                                </span>
                                </div>
                                <div class="col-lg-2 text-center text-lg-right mt-4 mt-lg-0 align ssl-zen-pro-upgrade">
                                    <button><?php 
                _e( 'UPGRADE', 'ssl-zen' );
                ?></button>
                                </div>
                            </div>
                        </a>
                    </footer>
                <?php 
            }
            
            ?>
            </div>
            <?php 
        }
        
        /**
         * Showing the steps navigation
         *
         * @param $step
         *
         * @since 2.0
         */
        public static function stepsNavigation( $step )
        {
            $isStep = ssl_zen_helper::stageIsStep( $step );
            ?>
            <section class="controls clearfix">
                <ul class="progress-list list-unstyled">
                    <?php 
            $passed = $isStep && $step > 'step1';
            ?>
                    <li class="<?php 
            echo  ( $step == 'step1' ? 'active' : '' ) ;
            ?> mr-2">
                        <a class="<?php 
            echo  ( $passed ? 'passed' : '' ) ;
            ?> mr-2"
                           href="<?php 
            echo  admin_url( 'admin.php?page=ssl_zen&tab=step1' ) ;
            ?>">
                            <?php 
            echo  ( $passed ? '' : 1 ) ;
            ?>
                        </a>
                        <span class="mr-2"><?php 
            _e( 'Website Details', 'ssl-zen' );
            ?></span>
                        <span></span>
                    </li>
                    <?php 
            $passed = $isStep && $step > 'step2';
            ?>
                    <li class="<?php 
            echo  ( $step == 'step2' ? 'active' : '' ) ;
            ?> mr-2">
                        <a class="<?php 
            echo  ( $passed ? 'passed' : '' ) ;
            ?> mr-2"
                           href="<?php 
            echo  admin_url( 'admin.php?page=ssl_zen&tab=step2' ) ;
            ?>">
                            <?php 
            echo  ( $passed ? '' : 2 ) ;
            ?>
                        </a>
                        <span class="mr-2"><?php 
            _e( 'Domain Verification', 'ssl-zen' );
            ?></span>
                        <span></span>
                    </li>
                    <?php 
            $passed = $isStep && $step > 'step3';
            ?>
                    <li class="<?php 
            echo  ( $step == 'step3' ? 'active' : '' ) ;
            ?> mr-2">
                        <a class="<?php 
            echo  ( $passed ? 'passed' : '' ) ;
            ?> mr-2"
                           href="<?php 
            echo  admin_url( 'admin.php?page=ssl_zen&tab=step3' ) ;
            ?>">
                            <?php 
            echo  ( $passed ? '' : 3 ) ;
            ?>
                        </a>
                        <span class="mr-2"><?php 
            _e( 'Install Certificate', 'ssl-zen' );
            ?></span>
                        <span></span>
                    </li>
                    <li class="last-child <?php 
            echo  ( $step == 'step4' ? 'active' : '' ) ;
            ?> mr-2">
                        <a class="mr-2"
                           href="<?php 
            echo  admin_url( 'admin.php?page=ssl_zen&tab=step4' ) ;
            ?>">4</a>
                        <span><?php 
            _e( 'Activate SSL', 'ssl-zen' );
            ?></span>
                    </li>
                </ul>
            </section>
            <?php 
        }
        
        /**
         * Show message container
         *
         * @since 2.0
         */
        private static function showMessage()
        {
            $info = ( !empty($_REQUEST['info']) ? sanitize_text_field( $_REQUEST['info'] ) : null );
            
            if ( !empty($info) ) {
                $messageArr = ssl_zen_messages::getMessage( $info );
                
                if ( !empty($messageArr) ) {
                    ?>
                    <section class="ssl-zen-message-container">
                        <div class="message <?php 
                    echo  ( empty($messageArr['type']) ? 'error' : $messageArr['type'] ) ;
                    ?> mb-5 ml-auto mr-auto">
                            <?php 
                    echo  $messageArr['msg'] ;
                    ?>
                        </div>
                    </section>
                    <?php 
                }
            
            }
        
        }
        
        /**
         * Function to display step 1 for SSL Zen.
         *
         * @since 1.0
         * @static
         */
        private static function step1()
        {
            $apiResponse = null;
            $image = 'lock';
            $heading = __( 'Secure your website with a free SSL certificate', 'ssl-zen' );
            $tagline = __( 'The SSL certificate for your website will be generated by LetsEncrypt.org, an open certificate authority (CA), run for the public\'s benefit.', 'ssl-zen' );
            ?>
            <form name="frmstep1" id="frmstep1" action="" method="post"
                  autocomplete="off">
                <?php 
            wp_nonce_field( 'ssl_zen_generate_certificate', 'ssl_zen_generate_certificate_nonce' );
            ?>
                <div class="ssl-zen-steps-container mb-4">
                    <div class="row">
                        <div class="col-12">
                            <p class="starting-quote">
                                <?php 
            echo  $heading ;
            ?>
                            </p>
                            <div class="media">
                                <div class="media-left">
                                    <img class="media-object"
                                         src="<?php 
            echo  SSL_ZEN_URL ;
            ?>img/<?php 
            echo  $image ;
            ?>.svg"
                                         alt="encrypt">
                                </div>
                                <div class="media-body">
                                    <p>
                                        <?php 
            echo  $tagline ;
            ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center p-4">
                        <div class="col-sm-3">
                            <div>
                                <?php 
            _e( 'Domain Details', 'ssl-zen' );
            ?>
                            </div>
                        </div>
                        <div class="col-sm-9 pt-4 pb-4">
                            <label for="domaiAdress"><?php 
            _e( 'Domain Address', 'ssl-zen' );
            ?></label>
                            <br>
                            <span class="text mb-3">
                                <?php 
            $urlInfo = parse_url( get_site_url() );
            $host = ( isset( $urlInfo['host'] ) ? $urlInfo['host'] : '' );
            echo  $host ;
            ?>
                            </span>
                            <input type="hidden" name="base_domain_name"
                                   id="base_domain_name"
                                   value="<?php 
            echo  $host ;
            ?>">
                            <?php 
            
            if ( sz_fs()->is_plan( 'cdn', true ) ) {
                ?>
                                <span class="mini-message d-block w-100"><?php 
                _e( 'The domain name you would like to point to the StackPath Edge.', 'ssl-zen' );
                ?></span>
                            <?php 
            }
            
            ?>

                            <?php 
            
            if ( !ssl_zen_helper::checkWWWSubDomainExistence( $host ) && !sz_fs()->is_plan( 'cdn', true ) ) {
                ?>
                                <div class="checkbox checkbox-success checkbox-circle">
                                    <input type="checkbox" class="styled" name="include_www" id="include_www"
                                           value="1" <?php 
                echo  ( get_option( 'ssl_zen_include_wwww', '' ) == '1' ? 'checked="checked"' : '' ) ;
                ?> >
                                    <label for="include_www">
                                        <?php 
                _e( 'Include www-prefixed version too?', 'ssl-zen' );
                ?> &nbsp;
                                        <a href="#"
                                           data-toggle="tooltip"
                                           data-placement="right"
                                           title="<?php 
                _e( 'By default, we generate SSL certificate only for domain.com. If user enters www.domain.com your website will show a not secure warning. Check this box to create a certificate for www.domain.com too. Make sure you have a CNAME or A record added for www in your domain panel.', 'ssl-zen' );
                ?>">
                                            <img src="<?php 
                echo  SSL_ZEN_URL ;
                ?>img/imp.svg" alt="">
                                        </a>
                                    </label>
                                </div>
                            <?php 
            }
            
            ?>
                        </div>
                        <!-- Additional two columns for showing message container -->
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <div class="message-container"></div>
                        </div>
                        <!-- end message container -->

                        <?php 
            
            if ( sz_fs()->is_plan( 'cdn', true ) ) {
                ?>
                            <div class="col-sm-3">
                                <div>
                                    <?php 
                _e( 'Hostname/IP Address', 'ssl-zen' );
                ?>
                                    &nbsp;
                                </div>
                            </div>
                            <div class="col-sm-9 pt-4 pb-4">
                                <label for="ip_address"><?php 
                _e( 'Hostname/IP Address', 'ssl-zen' );
                ?></label>
                                <br>
                                <span class="text mb-3">
									<?php 
                echo  $apiResponse['ip'] ;
                ?>
								</span>
                                <input type="hidden" name="ip_address"
                                       id="ip_address"
                                       value="<?php 
                echo  $apiResponse['ip'] ;
                ?>">
                                <span class="mini-message d-block w-100"><?php 
                _e( 'The IP address of your website.', 'ssl-zen' );
                ?></span>

                            </div>
                            <?php 
            } else {
                ?>
                            <div class="col-sm-3">
                                <div>
                                    <?php 
                _e( 'Contact Details', 'ssl-zen' );
                ?>
                                    &nbsp;
                                </div>
                            </div>
                            <div class="col-sm-9 pt-4 pb-4">
                                <label for="email"><?php 
                _e( 'Email Address', 'ssl-zen' );
                ?></label> <br>
                                <input type="email" name="email" id="email"
                                       placeholder="<?php 
                _e( 'Enter your email address', 'ssl-zen' );
                ?>"
                                       value="<?php 
                echo  get_option( 'ssl_zen_email', get_option( 'admin_email' ) ) ;
                ?>"
                                       required>
                            </div>
                            <?php 
            }
            
            ?>
                        <?php 
            // Check if cPanel is available on current site and Command line API isn't enabled
            
            if ( !ssl_zen_certificate::supportCPanelCommandLineApi() && ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite() ) {
                ?>
                            <div class="col-sm-3">
                                <div>
                                    <?php 
                _e( 'cPanel Details', 'ssl-zen' );
                ?> &nbsp;
                                </div>
                            </div>
                            <div class="col-sm-9 pt-4 pb-4">
                                <label for="ssl_zen_cpanel_username">
                                    <?php 
                _e( 'Username', 'ssl-zen' );
                ?>
                                </label>
                                <br>
                                <div style="min-height: 63px">
                                    <input type="text"
                                           name="ssl_zen_cpanel_username"
                                           id="ssl_zen_cpanel_username"
                                           autocomplete="off"
                                           placeholder="<?php 
                _e( 'Enter your cPanel username', 'ssl-zen' );
                ?>"
                                           value="<?php 
                echo  get_option( 'ssl_zen_cpanel_username' ) ;
                ?>"
                                           required>
                                </div>
                                <label for="ssl_zen_cpanel_password">
                                    <?php 
                _e( 'Password', 'ssl-zen' );
                ?>
                                </label>
                                <br>
                                <input type="password"
                                       name="ssl_zen_cpanel_password"
                                       id="ssl_zen_cpanel_password"
                                       autocomplete="off"
                                       placeholder="<?php 
                _e( 'Enter your cPanel password', 'ssl-zen' );
                ?>"
                                       value="<?php 
                echo  get_option( 'ssl_zen_cpanel_password' ) ;
                ?>"
                                       required>
                            </div>
                        <?php 
            }
            
            ?>
                        <?php 
            
            if ( !sz_fs()->is_plan( 'cdn', true ) ) {
                ?>
                            <div class="col-sm-3 mt-4"></div>
                            <div class="col-sm-9 mt-4">
                                <div class="checkbox checkbox-success checkbox-circle terms-checkbox">
                                    <input type="checkbox" class="styled"
                                           name="terms" id="terms" value="1"
                                           required>
                                    <label for="terms">
                                        <?php 
                echo  sprintf(
                    /* translators: 1: Start of link tag 2: End of link tag*/
                    __( 'I agree to %1$sTerms and Conditions%2$s', 'ssl-zen' ),
                    '<a href="https://sslzen.com/terms-of-service/" target="_blank">',
                    '</a>'
                ) ;
                ?>
                                    </label>
                                </div>
                            </div>
                        <?php 
            }
            
            ?>
                    </div>
                </div>
                <div class="text-right mb-4">
                    <a class="sslzen-step1-next-button primary next" href="#"><?php 
            _e( 'Next', 'ssl-zen' );
            ?></a>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Function to display step 2 (stackpath variation) for SSL Zen.
         *
         * @since 1.0
         * @static
         */
        private static function step2_stackpath()
        {
            if ( !sz_fs()->is_plan( 'cdn', true ) ) {
                return;
            }
            $notice = array();
            $diff = 10;
            $scanDnsButtonClass = '';
            $timerButtonClass = 'd-none';
            $nextButtonClass = 'disabled';
            $timerClass = 'timer-automatic timer-automatic-fire-ajax timer-automatic-enable-no';
            $image = 'done-circle';
            $ajaxData = array();
            $last_api_called = get_option( 'ssl_zen_last_auth_api_call' );
            $apiResponse = null;
            $domainconnectUrl = null;
            $nonce_field = 'ssl_zen_stackpath_verify_records';
            // if nonce is part of the payload, the user has come from step2.
            
            if ( $last_api_called === 'verify_records' || isset( $_POST[$nonce_field] ) && wp_verify_nonce( sanitize_text_field( $_POST[$nonce_field] ), 'ssl_zen_verify' ) ) {
                $apiResponse = ssl_zen_auth::call( 'verify_records' );
                
                if ( !$apiResponse || intval( $apiResponse['wait'] ) === 1 ) {
                    // stay on the same page and show a notice.
                    $notice['warning'] = ( empty($apiResponse['wait_reason']) ? __( 'We are verifying your DNS records. Please wait…', 'ssl-zen' ) : $apiResponse['wait_reason'] );
                    $scanDnsButtonClass = 'd-none';
                    $timerButtonClass = '';
                    $image = 'warning-circle';
                    $ajaxData = array(
                        'security' => wp_create_nonce( 'ssl_zen_ajax' ),
                        'action'   => 'ssl_zen_stackpath',
                        '_action'  => 'step2',
                    );
                } else {
                    $notice['success'] = __( 'You have successfully added your website to Stackpath.', 'ssl-zen' );
                    $scanDnsButtonClass = 'd-none';
                    $nextButtonClass = '';
                    update_option( 'ssl_zen_settings_stage', 'step3' );
                }
            
            } else {
                // user has come from step1.
                $apiResponse = ssl_zen_auth::call( 'add_site' );
                if ( 'settings' === $apiResponse['goto'] ) {
                    // redirect to settings.
                    wp_safe_redirect( admin_url( 'admin.php?page=ssl_zen&tab=settings' ) );
                }
                
                if ( $apiResponse && array_key_exists( 'wait', $apiResponse ) && ssl_zen_domainconnect::isEnabled() ) {
                    $domainconnectUrl = ssl_zen_domainconnect::getUrl( ssl_zen_helper::getStackpathEdgeName( $apiResponse['records'] ) );
                    // Fix for cases when domainconnectUrl is empty
                    
                    if ( $domainconnectUrl ) {
                        $scanDnsButtonClass = 'd-none';
                        $timerButtonClass = 'd-none';
                    }
                
                } else {
                    if ( array_key_exists( 'www', $apiResponse['records'] ) && stripos( ssl_zen_helper::getDomain(), 'www.' ) === false ) {
                        unset( $apiResponse['records']['www'] );
                    }
                }
            
            }
            
            ?>

            <div class="ssl-zen-steps-container mb-4 custom-round p-0">
                <div class="col-md-13 ssl-zen-domain-verification-variant-tab-container ">
                    <div class="row">
                        <div class="col-md-7 p-5">
                            <h4 class="mb-3">
                                <?php 
            _e( 'Domain Verification', 'ssl-zen' );
            ?>
                            </h4>
                            <p>
                                <?php 
            // Change message if a "Update DNS Record" is displayed
            _e( ( $domainconnectUrl ? 'Click on Update DNS Records to automatically update your DNS records on GoDaddy to start pointing to the StackPath Network.' : 'Your site is almost ready! Update your DNS to start pointing to the Stackpath Network.' ), 'ssl-zen' );
            ?>
                            </p>

                            <form name="frmstep2" id="frmstep2" class="stackpath" action="" method="post">
                                <?php 
            wp_nonce_field( 'ssl_zen_verify', $nonce_field );
            ?>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr class="grey">
                                        <th><?php 
            _e( 'Type', 'ssl-zen' );
            ?></th>
                                        <th><?php 
            _e( 'Name', 'ssl-zen' );
            ?></th>
                                        <th><?php 
            _e( 'Value', 'ssl-zen' );
            ?></th>
                                        <th><?php 
            _e( 'TTL', 'ssl-zen' );
            ?></th>
                                    </tr>
                                    <?php 
            foreach ( $apiResponse['records'] as $record ) {
                if ( empty($record['name']) ) {
                    continue;
                }
                $copy_class = '';
                $img_class = 'd-none';
                
                if ( isset( $apiResponse['correct_records'] ) ) {
                    $copy_class = ( in_array( $record['type'], $apiResponse['correct_records'], true ) ? 'd-none' : '' );
                    $img_class = ( in_array( $record['type'], $apiResponse['correct_records'], true ) ? '' : 'd-none' );
                }
                
                ?>
                                        <tr class="record_type_<?php 
                echo  esc_attr( $record['type'] ) ;
                ?>">
                                            <td><?php 
                echo  $record['type'] ;
                ?></td>
                                            <td><?php 
                echo  $record['name'] ;
                ?></td>
                                            <td>
                                                <?php 
                echo  $record['value'] ;
                ?>
                                                <i class="copy-clipboard <?php 
                echo  $copy_class ;
                ?>"
                                                   title="<?php 
                _e( 'Copy', 'ssl-zen' );
                ?>"
                                                   data-clipboard-text="<?php 
                echo  esc_attr( $record['value'] ) ;
                ?>"></i>
                                                <img class="record-done <?php 
                echo  $img_class ;
                ?>"
                                                     src="<?php 
                echo  SSL_ZEN_URL . 'img/success.svg' ;
                ?>" alt="">
                                            </td>
                                            <td><?php 
                echo  $record['ttl'] ;
                ?></td>
                                        </tr>
                                        <?php 
            }
            ?>
                                    </tbody>
                                </table>
                                <div class="align-items-center d-flex mt-3">
                                    <a class="scan-dns-stackpath primary next mr-3 w-50 <?php 
            echo  $scanDnsButtonClass ;
            ?>"
                                       data-ajax-data="<?php 
            echo  esc_attr( json_encode( $ajaxData ) ) ;
            ?>"><?php 
            _e( 'Scan DNS Records', 'ssl-zen' );
            ?>
                                        <img src="<?php 
            echo  SSL_ZEN_URL ;
            ?>img/<?php 
            echo  $image ;
            ?>.svg"></a>
                                    <span class="time-wait <?php 
            echo  $timerClass ;
            ?> <?php 
            echo  $timerButtonClass ;
            ?>"
                                          data-button=".scan-dns-stackpath" data-time="<?php 
            echo  $diff ;
            ?>"
                                          data-function="step2_mark_records_done"></span>
                                </div>
                                <form>
                                    <?php 
            
            if ( $domainconnectUrl ) {
                ?>
                                        <div class="align-items-center d-flex mt-3">
                                            <a class="update-dns-stackpath primary next mr-3 w-50"
                                               href="<?php 
                echo  $domainconnectUrl ;
                ?>"><?php 
                _e( 'Update DNS Records', 'ssl-zen' );
                ?>
                                                <img src="<?php 
                echo  SSL_ZEN_URL ;
                ?>img/done-circle.svg"></a>
                                        </div>
                                    <?php 
            }
            
            ?>
                                    <div class="message-container-2">
                                        <?php 
            if ( $notice ) {
                foreach ( $notice as $type => $message ) {
                    $extra = $class = '';
                    switch ( $type ) {
                        case 'warning':
                            $extra = '<span class="loader__dot">.</span><span class="loader__dot">.</span><span class="loader__dot">.</span>';
                            $class = 'sslzen-nowrap';
                            break;
                    }
                    echo  sprintf(
                        '<div class="message %s %s">%s%s</div>',
                        $type,
                        $class,
                        $message,
                        $extra
                    ) ;
                }
            }
            ?>
                                    </div>
                        </div>
                        <div class="col-md-5">

                            <div class="description pb-5 pt-5 pl-4 pr-4">
                                <h4 class="mb-4">
                                    <?php 
            _e( 'How to update DNS records?', 'ssl-zen' );
            ?>
                                    <br/>
                                    <a href="https://support.stackpath.com/hc/en-us/articles/360001105186-How-To-Configure-DNS-for-CDN-WAF-with-Your-Provider"
                                       class="tutorial ml-0"
                                       target="_blank"><?php 
            _e( 'Video Tutorial', 'ssl-zen' );
            ?></a>
                                </h4>
                                <ul>
                                    <li><?php 
            _e( 'Log in to your domain provider (e.g. GoDaddy).', 'ssl-zen' );
            ?></li>
                                    <li><?php 
            _e( 'Find your domain and click on it.', 'ssl-zen' );
            ?>
                                    <li><?php 
            _e( 'Find DNS Settings or just Settings.', 'ssl-zen' );
            ?></li>
                                    <li><?php 
            _e( 'Look for the A record and update it with the value displayed in the table on the left side.', 'ssl-zen' );
            ?> </li>
                                    <li><?php 
            _e( 'If you cannot enter TTL as 300, try 600 or the lowest value allowed by your domain provider.', 'ssl-zen' );
            ?></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mb-4">
                <a class="primary next <?php 
            echo  $nextButtonClass ;
            ?>"
                   href="<?php 
            echo  admin_url( 'admin.php?page=ssl_zen&tab=step3' ) ;
            ?>"><?php 
            _e( 'Next', 'ssl-zen' );
            ?></a>
            </div>

            <?php 
        }
        
        private static function step3_stackpath()
        {
            if ( !sz_fs()->is_plan( 'cdn', true ) ) {
                return;
            }
            $trustedImg = '<img src="' . SSL_ZEN_URL . 'img/success.svg" alt="">';
            ?>

            <form name="frmstep3" class="stackpath" id="frmstep3" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_verify', 'ssl_zen_cert_not_active_nonce' );
            ?>
                <div class="ssl-zen-steps-container p-0 mb-4">
                    <div class="row ssl-zen-activate-ssl-container">
                        <div class="col-md-8 steps">
                            <div>
                                <h4 class="mb-3">
                                    <?php 
            _e( 'Free Dedicated Certificate', 'ssl-zen' );
            ?>
                                </h4>
                                <h5>
                                    <?php 
            _e( 'Details', 'ssl-zen' );
            ?>
                                </h5>
                                <h6><?php 
            _e( 'Issued by', 'ssl-zen' );
            ?>
                                    : <?php 
            echo  ( empty($apiResponse['details']['issuer']) ? '-' : $apiResponse['details']['issuer'] ) ;
            ?></h6>
                                <h6><?php 
            _e( 'Trusted', 'ssl-zen' );
            ?>
                                    : <?php 
            echo  ( intval( $apiResponse['details']['trusted'] ) === 1 ? $trustedImg : '' ) ;
            ?></h6>
                                <h6><?php 
            _e( 'Expires on', 'ssl-zen' );
            ?>: <?php 
            $expires = '-';
            
            if ( !empty($apiResponse['details']['expirationDate']) ) {
                $date = DateTime::createFromFormat( 'Y-m-d\\TH:i:s\\Z', $apiResponse['details']['expirationDate'] );
                if ( $date ) {
                    $expires = $date->format( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
                }
            }
            
            echo  $expires ;
            ?>
                                </h6>
                                <h5 class="mt-4">
                                    <?php 
            _e( 'Hosts', 'ssl-zen' );
            ?>
                                </h5>
                                <?php 
            foreach ( $apiResponse['details']['subjectAlternativeNames'] as $host ) {
                ?>
                                    <p class="mb-0"><img src="<?php 
                echo  SSL_ZEN_URL ;
                ?>img/padlock.svg"
                                                         alt="">&nbsp;<?php 
                echo  $host ;
                ?></p>
                                <?php 
            }
            ?>


                                <?php 
            echo  '<div class="message-container-2 cstep3">' ;
            if ( $notice ) {
                foreach ( $notice as $type => $message ) {
                    $extra = '';
                    switch ( $type ) {
                        case 'warning':
                            $extra = '<span class="loader__dot">.</span><span class="loader__dot">.</span><span class="loader__dot">.</span>';
                            break;
                    }
                    echo  sprintf(
                        '<div class="message %s">%s%s</div>%s',
                        $type,
                        $message,
                        $extra,
                        $subtext
                    ) ;
                }
            }
            echo  '</div>' ;
            ?>

                                <div class="align-items-center d-flex mt-4">
                                    <a class="scan-ssl-stackpath primary next mr-3 w-50 <?php 
            echo  $scanButtonClass ;
            ?>"
                                       data-ajax-data="<?php 
            echo  esc_attr( json_encode( $ajaxData ) ) ;
            ?>"><?php 
            _e( 'Force Recheck', 'ssl-zen' );
            echo  file_get_contents( SSL_ZEN_DIR . 'img/' . $image . '.svg' ) ;
            ?></a>
                                    <span class="time-wait <?php 
            echo  $timerClass ;
            ?>" data-button=".scan-ssl-stackpath"
                                          data-time="<?php 
            echo  $diff ;
            ?>"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="text-right mb-4">
                    <a class="primary next <?php 
            echo  $nextButtonClass ;
            ?>"
                       href="#"><?php 
            _e( 'Next', 'ssl-zen' );
            ?></a>
                </div>

            </form>

            <?php 
        }
        
        /**
         * Function to display step 2 for SSL Zen.
         *
         * @since 1.0
         * @static
         */
        private static function step2()
        {
            // Get existing option for selected variant
            $selectedVariant = get_option( 'ssl_zen_domain_verification_variant', '' );
            $cPanel = ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite();
            ?>
            <form name="frmstep2" id="frmstep2" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_verify', 'ssl_zen_verify_nonce' );
            
            if ( empty($selectedVariant) ) {
                $showNextButton = true;
                ?>
                    <input type="hidden" id="ssl_zen_domain_verification"
                           name="ssl_zen_domain_verification"
                           value="http">
                    <input type="hidden" id="ssl_zen_sub_step"
                           name="ssl_zen_sub_step" value="1">
                    <div class="ssl-zen-steps-container mb-4">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <p class="verification-question">
                                    <?php 
                _e( 'Which domain verification process would you like to use?', 'ssl-zen' );
                ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="ssl-zen-domain-verification-variant-container http <?php 
                echo  ( $selectedVariant == 'http' || $selectedVariant == '' ? 'selected' : '' ) ;
                ?> p-4">
                                    <div class="d-flex justify-content-between mb-5">
                                        <div>
                                            <span class="font-weight-bold http">HTTP</span>
                                        </div>
                                        <div>
                                            <span class="minute">10 mins</span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h5><?php 
                _e( 'Step 1', 'ssl-zen' );
                ?></h5>
                                        <p><?php 
                _e( 'Create .well-known/acme-challenge folder ', 'ssl-zen' );
                ?></p>
                                    </div>
                                    <div>
                                        <h5><?php 
                _e( 'Step 2 ', 'ssl-zen' );
                ?></h5>
                                        <p><?php 
                _e( 'Upload verification file(s) ', 'ssl-zen' );
                ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ssl-zen-domain-verification-variant-container <?php 
                echo  ( $selectedVariant == 'dns' ? 'selected' : '' ) ;
                ?> p-4">
                                    <div class="d-flex justify-content-between mb-5">
                                        <div>
                                            <span class="font-weight-bold dns">DNS</span>
                                        </div>
                                        <div>
                                            <span class="minute">7 mins</span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h5><?php 
                _e( 'Step 1', 'ssl-zen' );
                ?></h5>
                                        <p><?php 
                _e( 'Identify your domain host', 'ssl-zen' );
                ?></p>
                                    </div>
                                    <div>
                                        <h5><?php 
                _e( 'Step 2', 'ssl-zen' );
                ?></h5>
                                        <p><?php 
                _e( 'Add a domain TXT record', 'ssl-zen' );
                ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
            } else {
                // If selected variant was HTTP , then we need to fetch pending authorizations for further download
                $arrPendingHttp = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_HTTP, false );
                $arrPendingDns = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_DNS, false );
                // Get verification status
                $showNextButton = get_option( 'ssl_zen_domain_verified', '' );
                // Get next DNS check time left and calc diff if it is not empty
                $dnsCheckActivation = get_option( 'ssl_zen_dns_check_activation', '' );
                $diff = ( !empty($dnsCheckActivation) ? $dnsCheckActivation - time() : null );
                // Logic for scan-dns button class and also timer class
                
                if ( empty($arrPendingDns) ) {
                    $scanDnsButtonClass = 'disabled';
                    $timerButtonClass = 'd-none';
                } else {
                    
                    if ( empty($diff) || $diff < 0 ) {
                        $scanDnsButtonClass = '';
                        $timerButtonClass = 'd-none';
                    } else {
                        $scanDnsButtonClass = 'disabled';
                        $timerButtonClass = '';
                    }
                
                }
                
                //TODO show success message in proper variant container or in general container(is stage step2 and is verified)
                ?>
                    <input type="hidden" id="ssl_zen_sub_step"
                           name="ssl_zen_sub_step" value="2">
                    <ul class="ssl-zen-domain-verification-variant-tabs d-flex m-0">
                        <li class="http <?php 
                echo  ( $selectedVariant == 'http' ? 'active' : '' ) ;
                ?>">HTTP
                        </li>
                        <li class="dns <?php 
                echo  ( $selectedVariant == 'dns' ? 'active' : '' ) ;
                ?>">DNS
                        </li>
                    </ul>
                    <div class="ssl-zen-steps-container <?php 
                echo  ( $selectedVariant == 'dns' ? 'p-0' : '' ) ;
                ?> mb-4 custom-round">
                        <div class="row">
                            <div class="col-md-12 ssl-zen-domain-verification-variant-tab-container http <?php 
                echo  ( $selectedVariant == 'http' ? '' : 'd-none' ) ;
                ?>">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <div class="border-bottom pb-5">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="mb-4">
                                                        <?php 
                _e( 'HTTP Verification', 'ssl-zen' );
                ?>

                                                        <?php 
                
                if ( $cPanel ) {
                    ?>
                                                            <a href="https://www.youtube.com/watch?v=9PT7r8TSHks"
                                                               class="tutorial ml-3"
                                                               target="_blank"><?php 
                    _e( 'Video Tutorial', 'ssl-zen' );
                    ?></a>

                                                        <?php 
                } else {
                    ?>
                                                            <a href="https://www.youtube.com/watch?v=XApeU26YcV8"
                                                               class="tutorial ml-3"
                                                               target="_blank"><?php 
                    _e( 'Video Tutorial', 'ssl-zen' );
                    ?></a>
                                                        <?php 
                }
                
                ?>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5><?php 
                _e( 'STEP 1', 'ssl-zen' );
                ?></h5>
                                                    <p><?php 
                _e( 'Create a folder to upload verification files', 'ssl-zen' );
                ?></p>
                                                </div>
                                                <div class="col-md-8">
                                                    <span><?php 
                _e( 'Navigate to the Folder where you have hosted WordPress.', 'ssl-zen' );
                ?></span><br>
                                                    <span><?php 
                _e( 'Create a folder', 'ssl-zen' );
                ?></span>
                                                    <span class="folder">.well-known</span>
                                                    <span><?php 
                _e( 'and inside it another folder', 'ssl-zen' );
                ?></span><br>
                                                    <span class="folder">acme-challenge</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mt-5">
                                        <h5><?php 
                _e( 'STEP 2', 'ssl-zen' );
                ?></h5>
                                        <p><?php 
                _e( 'Upload the verification file(s)', 'ssl-zen' );
                ?></p>
                                    </div>
                                    <div class="col-md-8 mt-5">
                                        <span><?php 
                _e( 'Download the file(s) below on your local computer and', 'ssl-zen' );
                ?></span>
                                        <br>
                                        <span><?php 
                _e( 'upload them in', 'ssl-zen' );
                ?></span>
                                        <span class="folder">.well-known/acme-challenge</span>
                                        <span>folder</span><br>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-md-8 mt-3">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <?php 
                if ( !empty($arrPendingHttp) ) {
                    foreach ( $arrPendingHttp as $index => $item ) {
                        ?>
                                                    <a href="<?php 
                        echo  admin_url( 'admin.php?page=ssl_zen&tab=step2&download=' . $index ) ;
                        ?>"
                                                       class="download-file primary mr-3"><?php 
                        echo  __( 'File', 'ssl-zen' ) . ' ' . ($index + 1) ;
                        ?>
                                                    </a>
                                                    <?php 
                    }
                }
                ?>
                                            <a class="scan-http primary mr-3 <?php 
                echo  ( empty($arrPendingHttp) ? 'disabled' : '' ) ;
                ?>"><?php 
                _e( 'Verify', 'ssl-zen' );
                ?></a>
                                            <div class="message-container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 ssl-zen-domain-verification-variant-tab-container dns <?php 
                echo  ( $selectedVariant == 'dns' ? '' : 'd-none' ) ;
                ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="ssl-zen-domain-verification-variant-tab-container-left">
                                            <h4 class="mb-4">
                                                <?php 
                _e( 'DNS Verification', 'ssl-zen' );
                ?>
                                                <a href="https://youtu.be/ubT5EpBr6-U"
                                                   class="tutorial ml-3"
                                                   target="_blank"><?php 
                _e( 'Video Tutorial', 'ssl-zen' );
                ?></a>
                                            </h4>
                                            <p><?php 
                _e( 'To verify domain ownership, you will need to create a DNS record of the
                                                TXT type as shown below.', 'ssl-zen' );
                ?>
                                            </p>
                                            <?php 
                
                if ( !empty($arrPendingDns) ) {
                    ?>
                                                <div class="record-table mt-4">
                                                    <div class="head"><?php 
                    _e( 'Domain TXT Record', 'ssl-zen' );
                    ?></div>
                                                    <div class="head"><?php 
                    _e( 'Value', 'ssl-zen' );
                    ?></div>
                                                    <?php 
                    foreach ( $arrPendingDns as $key => $item ) {
                        $rowClass = ( !$key ? 'first' : 'second' );
                        $value = ( ssl_zen_helper::checkWWWSubDomainExistence( $item['identifier'] ) ? '_acme-challenge.www' : '_acme-challenge' );
                        ?>
                                                        <div class="record <?php 
                        echo  $rowClass ;
                        ?> d-flex align-items-center justify-content-between">
                                                            <input class="acme"
                                                                   type="text"
                                                                   value="<?php 
                        echo  $value ;
                        ?>">
                                                            <i class="copy"
                                                               title="<?php 
                        _e( 'Copy', 'ssl-zen' );
                        ?>"></i>
                                                        </div>
                                                        <div class="record <?php 
                        echo  $rowClass ;
                        ?> d-flex align-items-center justify-content-between">
                                                            <input class="txt"
                                                                   type="text"
                                                                   value="<?php 
                        echo  $item['DNSDigest'] ;
                        ?>">
                                                            <i class="copy"
                                                               title="<?php 
                        _e( 'Copy', 'ssl-zen' );
                        ?>"></i>
                                                        </div>
                                                    <?php 
                    }
                    ?>
                                                </div>
                                            <?php 
                }
                
                ?>
                                            <div class="align-items-center d-flex mt-4">
                                                <a class="scan-dns primary mr-3 <?php 
                echo  $scanDnsButtonClass ;
                ?>">Scan
                                                    DNS Record</a>
                                                <?php 
                
                if ( !is_null( $diff ) && $diff > 0 ) {
                    ?>
                                                    <script>
                                                      var sslDnsCheckTimeLeft = <?php 
                    echo  $diff ;
                    ?>;
                                                    </script>
                                                <?php 
                }
                
                ?>
                                                <span class="time-wait <?php 
                echo  $timerButtonClass ;
                ?>">
                                                    <?php 
                echo  sprintf(
                    /* translators: %s: Milliseconds div */
                    __( 'Wait for %s to try again.', 'ssl-zen' ),
                    '<span class="ms"></span>'
                ) ;
                ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="description pb-5 pt-5 pl-4 pr-4">
                                            <h4><?php 
                _e( 'How to add a TXT record ?', 'ssl-zen' );
                ?></h4>
                                            <ul>
                                                <li><?php 
                _e( 'Sign in to your domain host.', 'ssl-zen' );
                ?></li>
                                                <li><?php 
                _e( 'Go to your domain’s DNS records.', 'ssl-zen' );
                ?>
                                                    <?php 
                _e( 'The page might be called something like', 'ssl-zen' );
                ?>
                                                    DNS Management, Name Server
                                                    Management, Control Panel,
                                                    or Advanced
                                                    Settings. <?php 
                _e( 'Select the option to add a new record.', 'ssl-zen' );
                ?>
                                                </li>
                                                <li><?php 
                _e( 'For the record type, select TXT', 'ssl-zen' );
                ?></li>
                                                <li><?php 
                _e( 'In the Name/Host/Alias field, enter ', 'ssl-zen' );
                ?> [
                                                    _acme-challenge ]
                                                </li>
                                                <li><?php 
                _e( 'In the TTL field, enter 300 or lower', 'ssl-zen' );
                ?></li>
                                                <li><?php 
                _e( 'In the Value/Answer/Destination field, paste the verification record and Save the record.', 'ssl-zen' );
                ?></li>
                                                <li><?php 
                _e( 'Come back here and click on Scan DNS Record button.', 'ssl-zen' );
                ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
            }
            
            ?>
                <div class="text-right mb-4">
                    <a class="primary next <?php 
            echo  $nextButtonClass ;
            ?>"
                       href="#"><?php 
            _e( 'Next', 'ssl-zen' );
            ?></a>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Function to display step 3 for SSL Zen.
         *
         * @since 1.0
         * @static
         */
        private static function step3()
        {
            // The url variable needed for checking the environment specifications
            $url = wp_parse_url( home_url() );
            $cPanel = ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite();
            $downloadLink = admin_url( 'admin.php?page=ssl_zen&tab=step3&download=' );
            ?>
            <form name="frmstep3" id="frmstep3" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_install_certificate', 'ssl_zen_install_certificate_nonce' );
            ?>
                <div class="ssl-zen-steps-container p-0 mb-4">
                    <div class="row ssl-zen-install-certificate-container">
                        <div class="col-lg-7 steps">
                            <div class="pt-5 pb-5 pl-5 pr-0">
                                <?php 
            
            if ( $cPanel ) {
                ?>
                                    <h4 class="mb-4">
                                        <?php 
                _e( 'Install SSL Certificate', 'ssl-zen' );
                ?>
                                        <a href="https://www.youtube.com/watch?v=UOPBUcym144"
                                           class="tutorial ml-3"
                                           target="_blank"><?php 
                _e( 'Video Tutorial', 'ssl-zen' );
                ?></a>
                                    </h4>
                                    <ul>
                                        <li>
                                            <a href="<?php 
                echo  site_url( 'cpanel' ) ;
                ?>"
                                               target="_blank"><?php 
                _e( 'Click here', 'ssl-zen' );
                ?></a>
                                            <span><?php 
                _e( 'to login into your cPanel account.', 'ssl-zen' );
                ?></span>
                                        </li>
                                        <li>
                                            <span><?php 
                _e( 'Locate and click on', 'ssl-zen' );
                ?></span>
                                            <span class="ssl-tls important"><?php 
                _e( 'SSL/TLS', 'ssl-zen' );
                ?></span>
                                            <span><?php 
                _e( 'icon in Security panel.', 'ssl-zen' );
                ?></span>
                                        </li>
                                        <li>
                                            <span><?php 
                _e( 'Click on', 'ssl-zen' );
                ?> </span>
                                            <span class="important"><?php 
                _e( 'Manage SSL sites', 'ssl-zen' );
                ?> </span>
                                            <span><?php 
                _e( 'under the Install and Manage SSL for your site.', 'ssl-zen' );
                ?></span>
                                        </li>
                                        <li>
                                            <span><?php 
                _e( 'Copy the contents of', 'ssl-zen' );
                ?> </span>
                                            <span class="important"><?php 
                _e( 'Certificate, Private Key & CA Bundle', 'ssl-zen' );
                ?></span>
                                            <span><?php 
                _e( 'file on the right and paste them in the relevant section in cPanel.', 'ssl-zen' );
                ?></span>
                                        </li>
                                    </ul>
                                <?php 
            } else {
                ?>
                                    <h4 class="mb-3">
                                        <?php 
                _e( 'Install SSL Certificate', 'ssl-zen' );
                ?>
                                    </h4>
                                    <p class="mb-3">
                                        <?php 
                _e( 'Depending on which server type you are looking to install your SSL certificate on, we have prepared a number of instructional guides.', 'ssl-zen' );
                ?>
                                        <?php 
                _e( 'Please choose your server type below to get installation instructions:', 'ssl-zen' );
                ?>
                                    </p>
                                    <ul class="ssl-zen-non-cpanel-external-links">
                                        <li>
                                            <a href="https://docs.sslzen.com/article/9-install-ssl-certificate-on-apache"
                                               target="_blank">
                                                <?php 
                _e( 'Install SSL Certificate on Apache', 'ssl-zen' );
                ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://docs.sslzen.com/article/14-installing-ssl-certificate-on-amazon-web-services-aws"
                                               target="_blank">
                                                <?php 
                _e( 'Install SSL Certificate on AWS', 'ssl-zen' );
                ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://docs.sslzen.com/article/13-installing-ssl-certificate-on-google-app-engine"
                                               target="_blank">
                                                <?php 
                _e( 'Install SSL Certificate on Google App Engine', 'ssl-zen' );
                ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://docs.sslzen.com/article/12-installing-ssl-certificate-on-nginx"
                                               target="_blank">
                                                <?php 
                _e( 'Install SSL Certificate on NGINX', 'ssl-zen' );
                ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://docs.sslzen.com/article/11-installing-ssl-certificate-on-plesk-12"
                                               target="_blank">
                                                <?php 
                _e( 'Install SSL Certificate on Plesk', 'ssl-zen' );
                ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://docs.sslzen.com/article/10-install-ssl-certificate-on-ubuntu"
                                               target="_blank">
                                                <?php 
                _e( 'Install SSL Certificate on Ubuntu', 'ssl-zen' );
                ?>
                                            </a>
                                        </li>
                                    </ul>
                                <?php 
            }
            
            ?>
                            </div>
                        </div>
                        <div class="col-lg-5 cpanel">
                            <div>
                                <div class="head"></div>
                                <div></div>
                                <div class="body">
                                    <ul>
                                        <li>
                                            <h6>Certificate : (CRT)</h6>
                                            <div>
                                                <div class="filename">certificate.crt</div>
                                                <div>
                                                    <i class="copy"
                                                       title="<?php 
            _e( 'Copy', 'ssl-zen' );
            ?>"
                                                       data-content="certificate.crt"></i>
                                                    <a title="<?php 
            _e( 'Download', 'ssl-zen' );
            ?>"
                                                       href="<?php 
            echo  $downloadLink . 'certificate' ;
            ?>"></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Private Key (KEY)</h6>
                                            <div>
                                                <div class="filename">privatekey.pem</div>
                                                <div>
                                                    <i class="copy"
                                                       title="<?php 
            _e( 'Copy', 'ssl-zen' );
            ?>"
                                                       data-content="privatekey.pem"></i>
                                                    <a title="<?php 
            _e( 'Download', 'ssl-zen' );
            ?>"
                                                       href="<?php 
            echo  $downloadLink . 'privatekey' ;
            ?>"></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <h6>Certificate Authority Bundle:
                                                (CABUNDLE)</h6>
                                            <div>
                                                <div class="filename">cabundle.crt</div>
                                                <div><i class="copy"
                                                        title="<?php 
            _e( 'Copy', 'ssl-zen' );
            ?>"
                                                        data-content="cabundle.crt"></i>
                                                    <a title="<?php 
            _e( 'Download', 'ssl-zen' );
            ?>"
                                                       href="<?php 
            echo  $downloadLink . 'cabundle' ;
            ?>"></a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ssl-zen-copy-certs-wrapper d-none justify-content-center align-items-center">
                        <div class="ssl-zen-copy-certs-container">
                            <div class="head d-flex align-items-center">
                                <span class="title"></span>
                                <div class="ml-auto mr-3 message d-none success"><?php 
            _e( 'Copied successfully', 'ssl-zen' );
            ?></div>
                                <div class="ml-auto mr-3 message d-none error"><?php 
            _e( 'Failed to copy', 'ssl-zen' );
            ?></div>
                                <span class="ml-auto mr-3 primary copy">Copy</span>
                                <span class="close"></span>
                            </div>
                            <div class="body"><textarea></textarea></div>
                        </div>
                    </div>
                </div>
                <div class="text-right mb-4">
                    <a class="primary next"
                       href="#"><?php 
            _e( 'Next', 'ssl-zen' );
            ?></a>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Function to display step 4 for SSL Zen.
         *
         * @since 1.0
         * @static
         */
        public static function step4()
        {
            $nonce_field = 'ssl_zen_activate_ssl_nonce';
            if ( sz_fs()->is_plan( 'cdn', true ) ) {
                $nonce_field = 'ssl_zen_activate_stackpath_cert';
            }
            ?>
            <form name="frmstep4" id="frmstep4" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_activate_ssl', $nonce_field );
            ?>
                <div class="ssl-zen-steps-container p-0 mb-4">
                    <div class="row ssl-zen-activate-ssl-container">
                        <div class="col-md-8 steps">
                            <div>
                                <h4 class="mb-4">
                                    <?php 
            _e( 'To start serving your wordpress website over SSL, we need to do the following:', 'ssl-zen' );
            ?>
                                </h4>
                                <ul>
                                    <?php 
            
            if ( sz_fs()->is_plan( 'cdn', true ) ) {
                ?>
                                        <li>
                                            <span><?php 
                _e( 'All incoming HTTP requests on your website will be redirected to HTTPS', 'ssl-zen' );
                ?></span>
                                        </li>
                                        <li>
                                            <span><?php 
                _e( 'Add code to wp-config.php to enable administration over SSL', 'ssl-zen' );
                ?></span>
                                        </li>
                                        <li>
                                            <span><?php 
                _e( 'Add code to avoid insecure content warning', 'ssl-zen' );
                ?></span>
                                        </li>
                                    <?php 
            } else {
                ?>
                                        <li>
                                            <span><?php 
                _e( 'All incoming HTTP requests on your website will be redirected to HTTPS', 'ssl-zen' );
                ?></span>
                                        </li>
                                        <li>
                                            <span><?php 
                _e( 'Your site URL and Home URL will be changed from HTTP  to HTTPS', 'ssl-zen' );
                ?></span>
                                        </li>
                                        <li>
                                            <span><?php 
                _e( 'We will fix insecure content warning by replacing HTTP URL\'s to HTTPS URL\'s', 'ssl-zen' );
                ?></span>
                                        </li>
                                    <?php 
            }
            
            ?>
                                </ul>
                                <?php 
            
            if ( !sz_fs()->is_plan( 'cdn', true ) && !(ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite() && sz_fs()->is_premium()) ) {
                // Note that in case we will show this section we need to disable the next button below
                ?>
                                    <div class="checkbox checkbox-success">
                                        <input type="checkbox" class="styled"
                                               name="ssl_zen_renew_confirm"
                                               id="ssl_zen_renew_confirm"
                                               value="1" required=""
                                               aria-required="true">
                                        <label for="ssl_zen_renew_confirm">
                                            <?php 
                echo  sprintf(
                    /* translators: 1: Start of important span 2: End of important span*/
                    __( 'If I don\'t renew my SSL certificate every %1$s 90 days %2$s,
                                                my website will start showing a', 'ssl-zen' ),
                    '<span class="important">',
                    '</span>'
                ) ;
                ?>
                                        </label>
                                        <div class="mt-2 note">
                                            <?php 
                echo  sprintf(
                    /* translators: 1: Start of important danger span 2: End of important danger span 3: Start of span 4: End of span*/
                    __( '%1$s Not Secure %2$s %3$s warning to my website visitors.%4$s', 'ssl-zen' ),
                    '<span class="important red-rect">',
                    '</span>',
                    '<span>',
                    '</span>'
                ) ;
                ?>
                                        </div>
                                    </div>
                                <?php 
            }
            
            ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <div class="note">
                                    <div class="head">
                                        <span class="important"><?php 
            _e( 'Note', 'ssl-zen' );
            ?></span>
                                    </div>
                                    <div class="body">
                                        <span><?php 
            _e( 'Remember to clear your browser cache after SSL is activated on your website.', 'ssl-zen' );
            ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mb-4">
                    <a class="primary next"
                       href="#"><?php 
            _e( 'Next', 'ssl-zen' );
            ?></a>
                </div>
            </form>
            <?php 
        }
        
        public static function review_notice()
        {
            // Get the right options from db, check if SSL is activated
            $is_activated = get_option( 'ssl_zen_ssl_activated', '' );
            $activated_time = get_option( 'ssl_zen_ssl_activated_date', '' );
            // This is for compatibility with old installations
            
            if ( $is_activated && !$activated_time ) {
                update_option( 'ssl_zen_ssl_activated_date', time() );
                $activated_time = time();
            }
            
            // Pick up the last review reminder
            $last_review_reminder = get_option( 'ssl_zen_review_reminder', '' );
            // If SSL is not activated
            if ( !$is_activated ) {
                return;
            }
            // Calculate the time to last review
            $time = time();
            $day = 86400;
            $diff = $time - $activated_time;
            $days = (int) ($diff / $day);
            // If we have already shown the reminder on the same day, don't show it.
            if ( $days == $last_review_reminder ) {
                return;
            }
            // Show notice on select dates only
            if ( !in_array( $days, [
                1,
                3,
                30,
                60,
                90
            ] ) ) {
                return;
            }
            // Set the new reminder day
            update_option( 'ssl_zen_review_reminder', $days );
            add_action( 'admin_notices', function () {
                $class = 'm-1 notice notice-info is-dismissible';
                $message = __( 'We just saved you $60/year in SSL Certificate fees! Could you please do us a big favour and review SSL Zen on wordpress.org?', 'ssl-zen' );
                $ok = sprintf( __( '%1$sYes%2$s', 'ssl-zen' ), '<a class="button button-primary" href="https://wordpress.org/support/plugin/ssl-zen/reviews/#new-post" target="_blank">', '</a>' );
                $remind_me_later = sprintf( __( '%1$sRemind me later%2$s', 'ssl-zen' ), '<a class="button" href="' . admin_url( 'admin.php?page=ssl_zen&tab=settings' ) . '">', '</a>' );
                print sprintf(
                    '<div class="%1$s"><p>%2$s</p><p>%3$s&nbsp;%4$s</p></div>',
                    $class,
                    $message,
                    $ok,
                    $remind_me_later
                );
            } );
        }
        
        /**
         * Method to show review and congratulations of successfully SSL activation
         *
         * @since 2.0
         */
        public static function review()
        {
            update_option( 'ssl_zen_settings_stage', 'settings' );
            ?>
            <form name="frmReview" id="frmReview" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_review', 'ssl_zen_review_nonce' );
            ?>
                <div class="ssl-zen-steps-container p-0 mb-4 border-0">
                    <div class="ssl-arrow"></div>
                    <div class="row ssl-zen-review-container">
                        <div class="col-md-10">
                            <div class="description pl-5 pr-0">
                                <div class="ssl mb-4">
                                    <div class="lock"></div>
                                    <div class="line"></div>
                                </div>
                                <h4><?php 
            _e( 'SSL Certificate Successfully Installed!', 'ssl-zen' );
            ?></h4>
                                <p class="saved-quote">
                                    <?php 
            _e( 'Wowzer! We just saved you $60/year in SSL Certificate fees.', 'ssl-zen' );
            ?>
                                </p>
                                <div class="propose d-lg-flex align-items-center">
                                    <?php 
            _e( 'Could you please do us a BIG favour and give SSL Zen a', 'ssl-zen' );
            ?>
                                    <i class="star ml-2 mr-2"></i>
                                    <i class="star mr-2"></i>
                                    <i class="star mr-2"></i>
                                    <i class="star mr-2"></i>
                                    <i class="star mr-2"></i>
                                    <?php 
            _e( 'on WordPress.org?', 'ssl-zen' );
            ?>
                                </div>
                                <a href="https://wordpress.org/support/plugin/ssl-zen/reviews/#new-post"
                                   target="_blank"
                                   class="review primary mt-4 mb-2"><?php 
            _e( 'LEAVE A REVIEW', 'ssl-zen' );
            ?></a>
                                <span class="review-timing"><?php 
            _e( 'It will only take few moments', 'ssl-zen' );
            ?></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex align-items-center remind" style="display: none!important;">
                                <a href="<?php 
            echo  admin_url( 'admin.php?page=ssl_zen&tab=settings' ) ;
            ?>">
                                    <?php 
            _e( 'REMIND ME LATER', 'ssl-zen' );
            ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Method for showing incompatibility of installing SSL due to cloudflare
         *
         * @since 3.1
         */
        private static function cloudflareDetectedState()
        {
            $heading = __( 'SSL certificate cannot be installed!', 'ssl-zen' );
            $message = sprintf( __( 'Due to technical limitations, it\'s currently not possible to install SSL certificate on CloudFlare hosted websites using our plugin. We are sorry for the inconvenience. %1$s Please watch the below video tutorial on how you can use CloudFlare Plugin (Unofficial) to get an SSL certificate on your website.%2$s', 'ssl-zen' ), "<br/>", '<br/><iframe width="560" height="315" src="https://www.youtube.com/embed/lPAt2nfgtPA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' );
            ?>
            <div class="ssl-zen-steps-container p-0 mb-4">
                <div class="row ssl-zen-error-state-container">
                    <div class="col-md-4">
                        <div class="mt-5 mb-5 banner"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="pt-5 pb-5 pr-5 pl-0">
                            <h4><?php 
            echo  $heading ;
            ?></h4>
                            <p><?php 
            echo  $message ;
            ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
        }
        
        /**
         * Method for showing incompatibility of installing SSL due to cloudflare
         *
         * @since 3.1
         */
        private static function bluehostDetectedState()
        {
            $heading = __( 'SSL certificate cannot be installed!', 'ssl-zen' );
            $message = sprintf( __( 'You are trying to install the SSL certificate on a temporary Bluehost domain. SSL certificates can only be installed on your own domain name. Please follow these instructions to replace your temporary domain name with your own domain name.%1$s %2$s', 'ssl-zen' ), '<p>Video Tutorial - <a href="https://www.youtube.com/watch?v=E2I_8C5vMf4">https://www.youtube.com/watch?v=E2I_8C5vMf4</a></p>', '<p>Article - <a href="https://www.bluehost.com/help/article/using-your-temporary-url-with-wordpress#changing-from-temp">https://www.bluehost.com/help/article/using-your-temporary-url-with-wordpress#changing-from-temp</a></p>' );
            ?>
            <div class="ssl-zen-steps-container p-0 mb-4">
                <div class="row ssl-zen-error-state-container">
                    <div class="col-md-4">
                        <div class="mt-5 mb-5 banner"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="pt-5 pb-5 pr-5 pl-0">
                            <h4><?php 
            echo  $heading ;
            ?></h4>
                            <p><?php 
            echo  $message ;
            ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
        }
        
        /**
         * Method for showing incompatibility of installing SSL
         *
         * @since 2.0
         */
        private static function errorState()
        {
            $heading = __( 'SSL certificate cannot be installed!', 'ssl-zen' );
            $message = __( 'Due to technical limitations, it\'s currently not possible to install SSL certificate on an IP address or a localhost. Please install the plugin on a publicly facing, worldwide unique domain name such as sslzen.com and try again.', 'ssl-zen' );
            ?>
            <div class="ssl-zen-steps-container p-0 mb-4">
                <div class="row ssl-zen-error-state-container">
                    <div class="col-md-4">
                        <div class="mt-5 mb-5 banner"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="pt-5 pb-5 pr-5 pl-0">
                            <h4><?php 
            echo  $heading ;
            ?></h4>
                            <p><?php 
            echo  $message ;
            ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
        }
        
        /**
         * Method for showing system requirements
         *
         * @since 2.0
         */
        private static function systemRequirements()
        {
            $systemRequirements = ssl_zen_helper::getSystemRequirementsStatus();
            // Check at least one false value
            $col = 3;
            foreach ( $systemRequirements as $key => $item ) {
                
                if ( !$item ) {
                    $col = 9;
                    break;
                }
            
            }
            ?>
            <form name="frmsysreq" id="frmsysreq" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_system_requirements', 'ssl_zen_system_requirements_nonce' );
            ?>
                <div class="ssl-zen-steps-container p-0 border-0">
                    <h4 class="ssl-zen-system-requirement-header pb-2 mb-4">
                        <?php 
            _e( 'System Requirements Check', 'ssl-zen' );
            ?>
                    </h4>
                    <div class="row ssl-zen-system-requirement-container">
                        <div class="col-lg-<?php 
            echo  $col ;
            ?>">
                            <table class="table table-bordered">
                                <tbody>
                                <tr class="grey">
                                    <th>Server</th>
                                    <th><?php 
            _e( 'Info', 'ssl-zen' );
            ?></th>
                                </tr>
                                <tr>
                                    <td>PHP Version > 5.6.20+</td>
                                    <td class="text-center">
                                        <?php 
            
            if ( $systemRequirements['php'] ) {
                ?>
                                            <i class="check"></i>
                                        <?php 
            } else {
                ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <?php 
                _e( 'Please ask your hosting provider to upgrade your PHP to the latest version.', 'ssl-zen' );
                ?>
                                                <i class="check error"></i>
                                            </div>
                                        <?php 
            }
            
            ?>
                                    </td>
                                </tr>
                                <tr class="grey">
                                    <td>cURL enabled</td>
                                    <td class="text-center">
                                        <?php 
            
            if ( $systemRequirements['curl'] ) {
                ?>
                                            <i class="check"></i>
                                        <?php 
            } else {
                ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <?php 
                _e( 'Please ask your hosting provider to enable cURL on your website server.', 'ssl-zen' );
                ?>
                                                <i class="check error"></i>
                                            </div>
                                        <?php 
            }
            
            ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>openSSL enabled</td>
                                    <td class="text-center">
                                        <?php 
            
            if ( $systemRequirements['openssl'] ) {
                ?>
                                            <i class="check"></i>
                                        <?php 
            } else {
                ?>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <?php 
                _e( 'Please ask your hosting provider to enable open SSL on your website server.', 'ssl-zen' );
                ?>
                                                <i class="check error"></i>
                                            </div>
                                        <?php 
            }
            
            ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <?php 
            
            if ( $col == 3 ) {
                ?>
                                <span class="mb-4 d-block mini-message">Success! You will be automatically redirected to the plugin page in few seconds ...</span>
                                <a href="#" id="next"
                                   class="d-inline-block primary">NEXT</a>
                                <input type="hidden"
                                       name="ssl_zen_system_requirements_status"
                                       value="5">
                            <?php 
            } else {
                ?>
                                <span class="d-block mb-4 error mini-message">Our plugin won’t work until you fix the issues above.</span>
                                <a href="#" id="reCheck"
                                   class="d-inline-block primary">RE-CHECK</a>
                                <input type="hidden"
                                       name="ssl_zen_system_requirements_status"
                                       value="0">
                            <?php 
            }
            
            ?>
                        </div>
                    </div>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Method for showing pricing details: premium/free stackpath
         *
         * @since 2.0
         */
        private static function pricing_stackpath()
        {
            
            if ( ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite() ) {
                self::pricing();
                return;
            }
            
            ?>
            <form name="frmpricing" id="frmpricing" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_pricing', 'ssl_zen_pricing_nonce' );
            ?>
                <div class="ssl-zen-steps-container p-0 border-0">
                    <div class="d-md-flex justify-content-between ssl-zen-pricing-header-banner mb-4">
                        <div>
                            <h1 class="mb-4">The complete SSL certificate <br>
                                solution for WordPress.</h1>
                            <p>Subscribe to our premium plan and <br>
                                save hundreds of dollars in SSL certificate
                                fees.</p>
                        </div>
                        <div></div>
                    </div>
                    <div class="row ssl-zen-pricing-container ">
                        <div class="col-md-12">
                            <table class="table table-bordered table-responsive-lg">
                                <tbody>
                                <tr>
                                    <td class="empty"></td>
                                    <td class="amount premium">
                                        <div>
                                            <h3>Premium</h3>
                                            <span>$49</span>
                                            <span class="perioud">/ year</span>
                                        </div>
                                    </td>
                                    <td class="amount free">
                                        <div>
                                            <h3>Free</h3>
                                            <span class="d-block">$0</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="grey">
                                    <td><?php 
            _e( 'Content Delivery Network', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Automatic', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Manual', 'ssl-zen' );
            ?></td>
                                </tr>
                                <tr>
                                    <td><?php 
            _e( 'SSL Certificate Installation', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Automatic', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Manual', 'ssl-zen' );
            ?></td>
                                </tr>
                                <tr class="grey">
                                    <td><?php 
            _e( 'SSL Certificate Renewal', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Automatic Renewal', 'ssl-zen' );
            ?></td>
                                    <td>
                                        <div class="d-flex align-items-center pricing-notice">
                                            <?php 
            _e( 'Manual', 'ssl-zen' );
            ?>
                                            <span class="pricing-notice mt-1">( <?php 
            _e( 'You have to renew your SSL certificate every 90 days', 'ssl-zen' );
            ?>
												)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php 
            _e( 'Website Security', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Yes', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'No', 'ssl-zen' );
            ?></td>
                                </tr>
                                <tr class="grey">
                                    <td><?php 
            _e( 'Support', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Priority Email Support', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Basic', 'ssl-zen' );
            ?></td>
                                </tr>
                                <tr>
                                    <td class="empty"></td>
                                    <td class="price premium">
                                        <a href="<?php 
            echo  add_query_arg( array(
                'checkout'      => 'true',
                'plan_id'       => 10884,
                'plan_name'     => 'cdn',
                'billing_cycle' => 'annual',
                'pricing_id'    => 11089,
                'currency'      => 'usd',
            ), sz_fs()->get_upgrade_url() ) ;
            ?>"
                                           class="primary"><?php 
            _e( 'Select plan', 'ssl-zen' );
            ?></a>
                                    </td>
                                    <td class="price free">
                                        <a href="#"
                                           class="primary"><?php 
            _e( 'Select plan', 'ssl-zen' );
            ?></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Method for showing pricing details: premium/free cpanel
         *
         * @since 2.0
         */
        private static function pricing()
        {
            
            if ( !ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite() ) {
                self::pricing_stackpath();
                return;
            }
            
            ?>
            <form name="frmpricing" id="frmpricing" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_pricing', 'ssl_zen_pricing_nonce' );
            ?>
                <div class="ssl-zen-steps-container p-0 border-0">
                    <div class="d-md-flex justify-content-between ssl-zen-pricing-header-banner mb-4">
                        <div>
                            <h1 class="mb-4">The complete SSL certificate <br>
                                solution for WordPress.</h1>
                            <p>Subscribe to our premium plan and <br>
                                save hundreds of dollars in SSL certificate
                                fees.</p>
                        </div>
                        <div></div>
                    </div>
                    <div class="row ssl-zen-pricing-container ">
                        <div class="col-md-12">
                            <table class="table table-bordered table-responsive-lg">
                                <tbody>
                                <tr>
                                    <td class="empty"></td>
                                    <td class="amount premium">
                                        <div>
                                            <h3>Premium</h3>
                                            <span>$29</span>
                                            <span class="perioud">/ year</span>
                                        </div>
                                    </td>
                                    <td class="amount free">
                                        <div>
                                            <h3>Free</h3>
                                            <span class="d-block">$0</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="grey">
                                    <td><?php 
            _e( 'Verify Domain Ownership', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Automatic', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Manual', 'ssl-zen' );
            ?></td>
                                </tr>
                                <tr>
                                    <td><?php 
            _e( 'SSL Certificate Installation', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Automatic', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Manual', 'ssl-zen' );
            ?></td>
                                </tr>
                                <tr class="grey">
                                    <td><?php 
            _e( 'SSL Certificate Renewal', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Automatic Renewal', 'ssl-zen' );
            ?></td>
                                    <td>
                                        <div class="d-flex align-items-center pricing-notice">
                                            <?php 
            _e( 'Manual', 'ssl-zen' );
            ?>
                                            <span class="pricing-notice mt-1">( <?php 
            _e( 'You have to renew your SSL certificate every 90 days', 'ssl-zen' );
            ?>
												)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php 
            _e( 'Support', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Priority Email Support', 'ssl-zen' );
            ?></td>
                                    <td><?php 
            _e( 'Basic', 'ssl-zen' );
            ?></td>
                                </tr>
                                <tr class="grey">
                                    <td><?php 
            _e( 'Time Required', 'ssl-zen' );
            ?></td>
                                    <td>60 <?php 
            _e( 'seconds', 'ssl-zen' );
            ?></td>
                                    <td>60 <?php 
            echo  __( 'minutes', 'ssl-zen' ) . '/' . __( 'year', 'ssl-zen' ) ;
            ?></td>
                                </tr>
                                <tr>
                                    <td class="empty"></td>
                                    <td class="price premium">
                                        <a href="<?php 
            echo  add_query_arg( array(
                'checkout'      => 'true',
                'plan_id'       => 7397,
                'plan_name'     => 'pro',
                'billing_cycle' => 'annual',
                'pricing_id'    => 7115,
                'currency'      => 'usd',
            ), sz_fs()->get_upgrade_url() ) ;
            ?>"
                                           class="primary"><?php 
            _e( 'Select plan', 'ssl-zen' );
            ?></a>
                                    </td>
                                    <td class="price free">
                                        <a href="#"
                                           class="primary"><?php 
            _e( 'Select plan', 'ssl-zen' );
            ?></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Function to check support php function exec
         *
         * @since 1.2
         * @static
         */
        private static function check_exec_support()
        {
            if ( !\function_exists( 'shell_exec' ) || !\function_exists( 'exec' ) ) {
                return 'exec_not_support';
            }
        }
        
        /**
         * Function to add cron for renew certificate to the Cron Jobs
         *
         * @since 1.2
         * @static
         */
        private static function setup_cron()
        {
        }
        
        /**
         * Function to renew certificate which is calling from cron
         *
         * @return array|bool|mixed
         * @since 1.2
         * @static
         *
         */
        public static function cron_ssl_renew()
        {
            return false;
        }
        
        /**
         * Function to display manage settings for SSL Zen.
         *
         * @since 1.0
         * @static
         */
        private static function settings()
        {
            var_dump( function_exists( 'shell_exec' ) );
            var_dump( function_exists( 'shell' ) );
            global  $wp_version ;
            // Define variables
            $currentTimestamp = strtotime( date_i18n( 'Y-m-d' ) );
            $expiryDate = get_option( 'ssl_zen_certificate_90_days', '' );
            $primaryDomain = get_option( 'ssl_zen_base_domain', '' );
            $currentSettingTab = get_option( 'ssl_zen_settings_stage', '' );
            $tabsToShow = array( 'status', 'debug' );
            if ( ssl_zen_helper::isTabAvailableAtThisStage( $currentSettingTab, 'settings.advanced', self::$allowedTabs ) ) {
                $tabsToShow[] = 'advanced';
            }
            // Get server status fields
            $serverStatusFields = ssl_zen_helper::getServerStatusFields();
            $wordpressStatusFields = ssl_zen_helper::getWordPressStatusFields();
            $issuer = "Let's Encrypt Authority X3";
            $miniMessage = $renewButtonClass = '';
            $deactivateMsg = __( 'You will be unable to renew your SSL certificate if you uninstall this plugin.', 'ssl-zen' );
            $activeTab = 'advanced';
            if ( $currentSettingTab !== 'settings' ) {
                $activeTab = 'debug';
            }
            
            if ( !empty($expiryDate) ) {
                // Calc days left
                $expiryTime = strtotime( $expiryDate );
                $timeLeft = $expiryTime - $currentTimestamp;
                $days = floor( $timeLeft / (60 * 60 * 24) );
                $renewalDate = get_option( 'ssl_zen_certificate_60_days', '' );
                $allowRenew = ( $renewalDate <= date_i18n( 'Y-m-d' ) ? true : false );
                // Days circle color category
                
                if ( $days >= 0 && $days <= 30 ) {
                    $circleColor = "#FA541C";
                } elseif ( $days > 30 && $days <= 60 ) {
                    $circleColor = "#e9ec00";
                } else {
                    $circleColor = "#73D13D";
                }
            
            }
            
            //			if ( sz_fs()->can_use_premium_code__premium_only() ) {
            //				$cron_success = self::cron_ssl_renew();
            //			}
            do_action( 'admin_notices' );
            ?>
            <form name="frmSettings" id="frmSettings" action="" method="post">
                <?php 
            wp_nonce_field( 'ssl_zen_settings', 'ssl_zen_settings_nonce' );
            ?>
                <ul class="ssl-zen-settings-tab-container d-flex mb-4">
                    <?php 
            
            if ( in_array( 'advanced', $tabsToShow, true ) ) {
                ?>
                        <li data-tab="advanced"
                            class="advanced <?php 
                echo  ( $activeTab === 'advanced' ? 'active' : '' ) ;
                ?>">
                            <?php 
                _e( 'Advanced', 'ssl-zen' );
                ?>
                        </li>
                    <?php 
            }
            
            ?>
                    <?php 
            
            if ( in_array( 'status', $tabsToShow, true ) ) {
                ?>
                        <li data-tab="status" class="status <?php 
                echo  ( $activeTab === 'status' ? 'active' : '' ) ;
                ?>">
                            <?php 
                _e( 'Status', 'ssl-zen' );
                ?>
                        </li>
                    <?php 
            }
            
            ?>
                    <?php 
            
            if ( in_array( 'debug', $tabsToShow, true ) ) {
                ?>
                        <li data-tab="debug" class="debug <?php 
                echo  ( $activeTab === 'debug' ? 'active' : '' ) ;
                ?>">
                            <?php 
                _e( 'Debug', 'ssl-zen' );
                ?>
                        </li>
                    <?php 
            }
            
            ?>
                </ul>
                <div class="ssl-zen-steps-container p-0 mb-4 border-0">
                    <?php 
            
            if ( in_array( 'advanced', $tabsToShow, true ) ) {
                ?>
                        <div class="row ssl-zen-settings-container advanced-container">
                            <div class="col-md-4">
                                <div class="table">
                                    <div class="head">Renew SSL Certificate</div>
                                    <div class="body">
                                        <ul>
                                            <li class="mb-4 mt-3">
                                                <span class="d-block title"><?php 
                _e( 'Issued to', 'ssl-zen' );
                ?></span>
                                                <span class="d-block"><?php 
                echo  $primaryDomain ;
                ?></span>
                                            </li>
                                            <li class="mb-4">
                                                <span class="d-block title"><?php 
                _e( 'Issued by', 'ssl-zen' );
                ?></span>
                                                <span class="d-block"><?php 
                echo  $issuer ;
                ?></span>
                                            </li>
                                            <li class="mb-4">
                                                <span class="d-block title mb-4">Certificate Validity</span>
                                                <div class="d-flex justify-content-start ">
                                                    <?php 
                
                if ( !empty($days) && !empty($circleColor) ) {
                    ?>
                                                        <div class="days-left-container">
                                                            <div class="days-num d-flex align-items-center justify-content-center">
                                                                <span><?php 
                    echo  $days ;
                    ?></span>
                                                                <span>days</span>
                                                            </div>
                                                            <div
                                                                class="days-left"
                                                                data-donutty
                                                                data-radius=15
                                                                data-text="days"
                                                                data-min=0
                                                                data-max=90
                                                                data-value=<?php 
                    echo  $days ;
                    ?>
                                                                data-thickness=3
                                                                data-padding=0
                                                                data-round=true
                                                                data-color="<?php 
                    echo  $circleColor ;
                    ?>"
                                                            >
                                                            </div>
                                                        </div>
                                                    <?php 
                }
                
                ?>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="#"
                                                   class="primary renew <?php 
                echo  $renewButtonClass ;
                ?> <?php 
                echo  ( empty($allowRenew) ? 'disabled' : '' ) ;
                ?>">RENEW
                                                    CERTIFICATE</a>
                                                <?php 
                
                if ( $miniMessage ) {
                    ?>
                                                    <span class="mini-message d-block w-100"><?php 
                    echo  $miniMessage ;
                    ?></span>
                                                <?php 
                }
                
                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="table">
                                    <div class="head"><?php 
                _e( 'Advanced settings', 'ssl-zen' );
                ?></div>
                                    <div class="body right">
                                        <ul class="mb-4">
                                            <?php 
                
                if ( sz_fs()->is_plan( 'cdn', true ) ) {
                    ?>
                                                <li class="mb-4 line">
                                                    <label for="stackpath_purge_everything"
                                                           class="d-block title"><?php 
                    _e( 'Purge Everything', 'ssl-zen' );
                    ?></label>
                                                    <span><?php 
                    _e( 'Remove files from cache globally and retrieve them from your origin again the next time they are requested.', 'ssl-zen' );
                    ?></span>
                                                    <div class="mb-5 mt-2">
                                                        <a href="#"
                                                           class="d-inline-block primary stackpath-purge sslzen-form-button"
                                                           data-hidden="#stackpath_purge_all"
                                                           data-hidden-value="1"><?php 
                    echo  strtoupper( __( 'Purge Everything', 'ssl-zen' ) ) ;
                    ?></a>
                                                        <input type="hidden" name="stackpath_purge_all"
                                                               id="stackpath_purge_all">
                                                    </div>
                                                </li>
                                                <li class="d-flex mb-4 line">
                                                    <div>
                                                        <input class="toggle-event"
                                                               name="stackpath_auto_purge"
                                                               id="stackpath_auto_purge"
                                                               type="checkbox"
                                                            <?php 
                    echo  ( get_option( 'ssl_zen_stackpath_auto_purge', '' ) == '1' ? 'checked="checked"' : '' ) ;
                    ?> >
                                                    </div>
                                                    <div>
                                                        <label for="stackpath_auto_purge"
                                                               class="d-block title"><?php 
                    _e( 'Auto Purge', 'ssl-zen' );
                    ?></label>
                                                        <span><?php 
                    _e( 'Automatically purge pages and posts as they are updated in WordPress.', 'ssl-zen' );
                    ?></span>
                                                    </div>
                                                </li>
                                                <li class="d-flex mb-4 line">
                                                    <div>
                                                        <input class="toggle-event"
                                                               name="stackpath_bypass_cache"
                                                               id="stackpath_bypass_cache"
                                                               type="checkbox"
                                                            <?php 
                    echo  ( get_option( 'ssl_zen_stackpath_bypass_cache', '' ) == '1' ? 'checked="checked"' : '' ) ;
                    ?> >
                                                    </div>
                                                    <div>
                                                        <label for="stackpath_bypass_cache"
                                                               class="d-block title"><?php 
                    _e( 'Bypass Cache for WordPress cookies', 'ssl-zen' );
                    ?></label>
                                                        <span><?php 
                    _e( '[wp-*, wordpress, comment_*, woocommerce_*]', 'ssl-zen' );
                    ?></span>
                                                    </div>
                                                </li>
                                            <?php 
                } else {
                    ?>
                                                <li class="d-flex mb-4 line">
                                                    <div>
                                                        <input class="toggle-event"
                                                               name="enable_301_htaccess_redirect"
                                                               id="enable_301_htaccess_redirect"
                                                               type="checkbox"
                                                            <?php 
                    echo  ( get_option( 'ssl_zen_enable_301_htaccess_redirect', '' ) == '1' ? 'checked="checked"' : '' ) ;
                    ?> >
                                                    </div>
                                                    <div>
                                                        <label for="enable_301_htaccess_redirect"
                                                               class="d-block title"><?php 
                    _e( 'Enable 301 .htaccess redirect', 'ssl-zen' );
                    ?></label>
                                                        <span><?php 
                    _e( 'Speeds up your website but might also cause a redirect loop and lock you out of your website.', 'ssl-zen' );
                    ?></span>
                                                    </div>
                                                </li>
                                                <li class="d-flex mb-4 line">
                                                    <div>
                                                        <input class="toggle-event"
                                                               id="lock_htaccess_file"
                                                               name="lock_htaccess_file"
                                                               type="checkbox"
                                                            <?php 
                    echo  ( get_option( 'ssl_zen_lock_htaccess_file', '' ) == '1' ? 'checked="checked"' : '' ) ;
                    ?> >
                                                    </div>
                                                    <div>
                                                        <label for="lock_htaccess_file"
                                                               class="d-block title"><?php 
                    _e( 'Lock down .htaccess file', 'ssl-zen' );
                    ?></label>
                                                        <span><?php 
                    _e( 'Disables the plugin from making any changes so you can edit the file manually.', 'ssl-zen' );
                    ?></span>
                                                    </div>
                                                </li>
                                                <li class="d-flex line">
                                                    <div class="d-flex justify-content-start align-items-start">
                                                        <span class="grey-tri"></span>
                                                    </div>
                                                    <div>
                                                        <span class="d-block title"><?php 
                    _e( 'Don’t see a Padlock yet?', 'ssl-zen' );
                    ?></span>
                                                        <span>We highly recommend that you install</span>
                                                        <a href=" https://wordpress.org/plugins/really-simple-ssl/"
                                                           target="_blank">Really Simple
                                                            SSL</a> ,<span> a free plugin that will fix your mixed content errors.</span>
                                                    </div>
                                                </li>
                                            <?php 
                }
                
                ?>
                                        </ul>
                                        <?php 
                ?>

                                        <div class="mb-2 d-flex justify-content-between">
                                            <a href="#"
                                               class="d-inline-block error primary deactivate">DEACTIVATE
                                                PLUGIN</a>
                                            <a href="#"
                                               class="d-inline-block primary save">SAVE</a>
                                        </div>
                                        <div class="message info mt-4">
                                            <?php 
                echo  sprintf(
                    /* translators: 1: Link tag start 2: Link tag close */
                    __( 'Would you like to use SSL Zen plugin in your local language? Click %1$shere%2$s to contribute.' ),
                    '<a href="https://translate.wordpress.org/projects/wp-plugins/ssl-zen/">',
                    '</a>'
                ) ;
                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
            }
            
            ?>
                    <?php 
            
            if ( !sz_fs()->is_plan( 'cdn', true ) ) {
                $extraClass = ( in_array( 'advanced', $tabsToShow, true ) || $activeTab !== 'status' ? 'd-none' : '' );
                ?>
                        <div class="row ssl-zen-settings-container status-container <?php 
                echo  $extraClass ;
                ?>">
                            <div class="col-md-5">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr class="grey">
                                        <th>Server</th>
                                        <th>Info</th>
                                    </tr>
                                    <?php 
                foreach ( $serverStatusFields as $key => $field ) {
                    ?>
                                        <tr>
                                            <td><?php 
                    echo  $key ;
                    ?></td>
                                            <td><?php 
                    echo  $field ;
                    ?></td>
                                        </tr>
                                    <?php 
                }
                ?>
                                    </tbody>
                                </table>
                                <a href="<?php 
                echo  admin_url( 'admin.php?page=ssl_zen&tab=settings&download=status_info' ) ;
                ?>"
                                   class="d-inline-block primary mb-2 download-status">Download
                                    Status Info</a>
                                <span class="d-block mini-message"><?php 
                _e( 'When asked, please download and share this file with SSL Zen support team.', 'ssl-zen' );
                ?></span>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr class="grey">
                                        <th>WordPress</th>
                                        <th>Info</th>
                                    </tr>
                                    <?php 
                foreach ( $wordpressStatusFields as $key => $field ) {
                    ?>
                                        <tr>
                                            <td><?php 
                    echo  $key ;
                    ?></td>
                                            <td><?php 
                    echo  $field ;
                    ?></td>
                                        </tr>
                                    <?php 
                }
                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php 
            }
            
            ?>

                    <?php 
            self::show_debug_container( $tabsToShow, $activeTab );
            ?>
                </div>
            </form>
            <?php 
        }
        
        /**
         * Shows the container that shows the debug related markup.
         *
         * @since ?
         * @static
         */
        private static function show_debug_container( $tabsToShow, $activeTab )
        {
            
            if ( sz_fs()->is_plan( 'cdn', true ) && in_array( 'debug', $tabsToShow, true ) ) {
                // Check for the domain's IP address,
                // if they have accidentally changed the IP to stackpath for a reset
                $detectedStackPathIp = null;
                $apiResponse = ssl_zen_auth::call( 'get_ip' );
                if ( in_array( $apiResponse['ip'], ssl_zen_helper::$STACKPATH_IP ) ) {
                    $detectedStackPathIp = $apiResponse['ip'];
                }
                ?>
                <div class="row ssl-zen-settings-container debug-container">
                    <div class="col-md-9">
                        <ul class="mb-4">
                            <li class="d-flex mb-4 line">
                                <div>
                                    <input class="toggle-event"
                                           type="checkbox"
                                           id="enable_debug"
                                           name="enable_debug"
                                        <?php 
                echo  ( get_option( 'ssl_zen_show_debug_url', '' ) == '1' ? 'checked="checked"' : '' ) ;
                ?> >
                                </div>
                                <div>
                                    <label for="enable_debug"
                                           class="d-block title"><?php 
                _e( 'Show Debug URL', 'ssl-zen' );
                ?></label>
                                    <span><?php 
                _e( 'Generates the debug log for sharing with the support team.', 'ssl-zen' );
                ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="message-container-2">
                            <?php 
                $url = get_option( 'ssl_zen_debug_url' );
                if ( $url ) {
                    echo  sprintf(
                        '<div class="message success">%s <i class="copy-clipboard" title="%s" data-clipboard-text="%s"></i></div><div class="message-container"></div>',
                        $url,
                        __( 'Copy', 'ssl-zen' ),
                        $url
                    ) ;
                }
                ?>
                        </div>

                        <a
                            href="#"
                            class="d-inline-block primary mb-2 stackpath-reset sslzen-form-button"
                            data-hidden="#stackpath_reset_plugin"
                            data-hidden-value="<?php 
                echo  ( !$detectedStackPathIp ? '2' : '1' ) ;
                ?>"
                        >
                            <?php 
                _e( 'Reset Plugin', 'ssl-zen' );
                ?>
                        </a>
                        <input type="hidden" name="stackpath_reset_plugin" id="stackpath_reset_plugin">
                        <span class="d-block mini-message"><?php 
                _e( 'This will reset the plugin and allow you to start from the beginning.', 'ssl-zen' );
                ?></span>
                        <?php 
                
                if ( $detectedStackPathIp ) {
                    ?>
                        <div class="message error">
                            <?php 
                    echo  sprintf(
                        /* translators: 1: StackPath IP 2: Host IP */
                        __( 'Your website DNS record for type A is currently pointing to StackPath\'s IP - %1$s. Please change your website A record to %2$s and CNAME record for "www" to your domain before we can reset the plugin' ),
                        $detectedStackPathIp,
                        get_option( 'ssl_zen_stackpath_host_ip' )
                    ) ;
                    ?>
                        </div>
                        <?php 
                }
                
                ?>
                    </div>
                </div>
                <?php 
            } else {
                $extraClass = ( $activeTab === 'debug' ? '' : 'd-none' );
                // Get debug file if it exists
                $debugLog = ( file_exists( SSL_ZEN_DIR . 'log/debug.log' ) ? file_get_contents( SSL_ZEN_DIR . 'log/debug.log' ) : '' );
                ?>
                <div class="row ssl-zen-settings-container debug-container <?php 
                echo  $extraClass ;
                ?>">
                    <div class="col-md-9">
                        <ul class="mb-4">
                            <li class="d-flex mb-4 line">
                                <div>
                                    <input class="toggle-event"
                                           type="checkbox"
                                           id="enable_debug"
                                           name="enable_debug"
                                        <?php 
                echo  ( get_option( 'ssl_zen_enable_debug', '' ) == '1' ? 'checked="checked"' : '' ) ;
                ?> >
                                </div>
                                <div>
                                    <label for="enable_debug"
                                           class="d-block title"><?php 
                _e( 'Enable Debugging', 'ssl-zen' );
                ?></label>
                                    <span><?php 
                _e( 'Enables LOG_DEBUG for full debugging. Only enable when asked by the support team.', 'ssl-zen' );
                ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="table">
                            <div class="head"><?php 
                _e( 'Debug Log', 'ssl-zen' );
                ?></div>

                            <div class="body p-0">
                                <textarea class="border-0 w-100 p-4"><?php 
                echo  $debugLog ;
                ?></textarea>
                            </div>

                            <a href="<?php 
                echo  admin_url( 'admin.php?page=ssl_zen&tab=settings&download=debug_log' ) ;
                ?>"
                               class="d-inline-block primary mb-2 download-debug"><?php 
                _e( 'Download Debug Log', 'ssl-zen' );
                ?></a>
                            <span class="d-block mini-message"><?php 
                _e( 'When asked, please download and share this file with SSL Zen support team.', 'ssl-zen' );
                ?></span>
                        </div>
                    </div>
                </div>
                <?php 
            }
        
        }
        
        /**
         * Hook to be called when 'admin_init' action is called by wordpress.
         * Handles all the processing on the various setting steps as well as
         * redirection for incorrect steps
         *
         * @since 1.0
         * @static
         */
        public static function admin_init()
        {
            // let's avoid the header already sent error in case we want to redirect somewhere
            ob_start();
            $systemRequirementsNonce = ( isset( $_POST['ssl_zen_system_requirements_nonce'] ) ? sanitize_text_field( $_POST['ssl_zen_system_requirements_nonce'] ) : null );
            $sslZenPricingNonce = ( isset( $_POST['ssl_zen_pricing_nonce'] ) ? sanitize_text_field( $_POST['ssl_zen_pricing_nonce'] ) : null );
            $certificateNonce = ( isset( $_POST['ssl_zen_generate_certificate_nonce'] ) ? sanitize_text_field( $_POST['ssl_zen_generate_certificate_nonce'] ) : null );
            $verifyNonce = ( isset( $_POST['ssl_zen_verify_nonce'] ) ? sanitize_text_field( $_POST['ssl_zen_verify_nonce'] ) : null );
            $installCertificateNonce = ( isset( $_POST['ssl_zen_install_certificate_nonce'] ) ? sanitize_text_field( $_POST['ssl_zen_install_certificate_nonce'] ) : null );
            $activateSslNonce = ( isset( $_POST['ssl_zen_activate_ssl_nonce'] ) ? sanitize_text_field( $_POST['ssl_zen_activate_ssl_nonce'] ) : null );
            $settingsNonce = ( isset( $_POST['ssl_zen_settings_nonce'] ) ? sanitize_text_field( $_POST['ssl_zen_settings_nonce'] ) : null );
            // @TODO the below endless endifs should be changes to this switch format for easy maintenance and code readbility.
            $action = ( isset( $_POST['ssl_zen_activate_stackpath_cert'] ) && wp_verify_nonce( sanitize_text_field( $_POST['ssl_zen_activate_stackpath_cert'] ), 'ssl_zen_activate_ssl' ) ? 'stackpathStep4' : null );
            switch ( $action ) {
                case 'stackpathStep4':
                    $siteUrl = str_replace( "http://", "https://", get_option( 'siteurl' ) );
                    $homeUrl = str_replace( "http://", "https://", get_option( 'home' ) );
                    update_option( 'siteurl', $siteUrl );
                    update_option( 'home', $homeUrl );
                    update_option( 'ssl_zen_ssl_activated', '1' );
                    update_option( 'ssl_zen_ssl_activated_date', time() );
                    update_option( 'ssl_zen_settings_stage', 'review' );
                    ssl_zen_helper::removeLogs();
                    self::fix_wp_config();
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=review' ) );
                    die;
                    break;
            }
            // Check system requirements step
            
            if ( !empty($systemRequirementsNonce) && wp_verify_nonce( $systemRequirementsNonce, 'ssl_zen_system_requirements' ) ) {
                // Check system status flag
                $systemRequirementsStatus = ( isset( $_POST['ssl_zen_system_requirements_status'] ) ? sanitize_text_field( $_POST['ssl_zen_system_requirements_status'] ) : null );
                
                if ( !empty($systemRequirementsStatus) ) {
                    // Requirements are ok, then check cPanel availability (and also plan) then redirect properly
                    
                    if ( !sz_fs()->is_premium() ) {
                        // move to pricing page
                        update_option( 'ssl_zen_settings_stage', 'pricing' );
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=pricing' ) );
                        exit;
                    } else {
                        // If not enabled then move to step1
                        update_option( 'ssl_zen_settings_stage', 'step1' );
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step1' ) );
                        exit;
                    }
                
                } else {
                    // Also we are able to omit this cause anyway we will get back to same stage
                    // But we leave this , cause maybe we will add error message via GET
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=system_requirements&message' ) );
                    exit;
                }
            
            } elseif ( !empty($sslZenPricingNonce) && wp_verify_nonce( $sslZenPricingNonce, 'ssl_zen_pricing' ) ) {
                // We have submitted from pricing page by selecting the free plan. So now need to move to step 1
                update_option( 'ssl_zen_settings_stage', 'step1' );
                wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step1' ) );
                exit;
            } elseif ( !empty($certificateNonce) && wp_verify_nonce( $certificateNonce, 'ssl_zen_generate_certificate' ) ) {
                // Executed when submitted from step 1
                // Define vars from sanitized POST
                $includeWWW = ( isset( $_POST['include_www'] ) ? sanitize_text_field( $_POST['include_www'] ) : '0' );
                $baseDomain = ( isset( $_POST['base_domain_name'] ) ? sanitize_text_field( $_POST['base_domain_name'] ) : '' );
                $email = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '' );
                $ip_address = ( isset( $_POST['ip_address'] ) ? $_POST['ip_address'] : '' );
                // Weird situation when our response returned are empty
                
                if ( sz_fs()->is_plan( 'cdn', true ) && !$ip_address ) {
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step1&info=invalid_ip_address' ) );
                    die;
                }
                
                $arrDomains = array( $baseDomain );
                
                if ( !ssl_zen_helper::checkWWWSubDomainExistence( $baseDomain ) ) {
                    // Include www sub domain
                    if ( !empty($includeWWW) ) {
                        $arrDomains[] = 'www.' . $baseDomain;
                    }
                } else {
                    // Include non www domain too
                    $arrDomains[] = preg_replace(
                        '/www./',
                        '',
                        $baseDomain,
                        1
                    );
                }
                
                // Save form options in the db
                update_option( 'ssl_zen_include_wwww', $includeWWW );
                update_option( 'ssl_zen_domains', $arrDomains );
                update_option( 'ssl_zen_base_domain', $baseDomain );
                update_option( 'ssl_zen_email', $email );
                // If premium or free and not support cPanel command line then store the values, cause they have send before
                
                if ( !ssl_zen_certificate::supportCPanelCommandLineApi() ) {
                    $cPanelUsername = ( isset( $_POST['ssl_zen_cpanel_username'] ) ? sanitize_text_field( $_POST['ssl_zen_cpanel_username'] ) : '' );
                    $cPanelPassword = ( isset( $_POST['ssl_zen_cpanel_password'] ) ? sanitize_text_field( $_POST['ssl_zen_cpanel_password'] ) : '' );
                    update_option( 'ssl_zen_cpanel_username', $cPanelUsername );
                    update_option( 'ssl_zen_cpanel_password', $cPanelPassword );
                }
                
                if ( !sz_fs()->is_plan( 'cdn', true ) ) {
                    // Check with lets debug
                    ssl_zen_certificate::debugLetsEncrypt( $baseDomain );
                }
                // Empty existing keys directory in the plugin
                ssl_zen_helper::deleteAll( SSL_ZEN_DIR . 'keys', true );
                // Remove http verification files, no meter what variant have used before
                ssl_zen_helper::deleteAll( ABSPATH . '.well-known/acme-challenge', true );
                update_option( 'ssl_zen_settings_stage', 'step2' );
                wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2' ) );
                exit;
            } elseif ( !empty($verifyNonce) && wp_verify_nonce( $verifyNonce, 'ssl_zen_verify' ) ) {
                // Executed when submitted from step 2
                $subStep = ( !empty($_POST['ssl_zen_sub_step']) ? sanitize_text_field( $_POST['ssl_zen_sub_step'] ) : null );
                // Check sub steps
                
                if ( $subStep == 1 ) {
                    // First sub step
                    $variant = ( !empty($_POST['ssl_zen_domain_verification']) ? sanitize_text_field( $_POST['ssl_zen_domain_verification'] ) : null );
                    
                    if ( $variant == 'http' || $variant == 'dns' ) {
                        // Generate order
                        ssl_zen_certificate::generateOrder();
                        // Store choosed variant
                        update_option( 'ssl_zen_domain_verification_variant', $variant );
                    } else {
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=variant_error' ) );
                        exit;
                    }
                    
                    // Again redirect to step 2, for continue the flow
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2' ) );
                    exit;
                } elseif ( $subStep == 2 ) {
                    // Check auth
                    
                    if ( ssl_zen_certificate::validateAuthorization() ) {
                        // Finalize the order for SSL Certificate
                        ssl_zen_certificate::finalizeOrder();
                        // Generate SSL Certificate
                        $arrCertificates = ssl_zen_certificate::generateCertificate();
                        // E-Mail certificates to the user if certificates are generated successfully
                        
                        if ( is_array( $arrCertificates ) && count( $arrCertificates ) > 0 ) {
                            
                            if ( class_exists( 'ZipArchive' ) ) {
                                $zip = new ZipArchive();
                                $zip->open( SSL_ZEN_DIR . 'keys/certificates.zip', ZipArchive::CREATE );
                                foreach ( $arrCertificates as $certificate ) {
                                    $certificateName = str_replace( SSL_ZEN_DIR . 'keys/', '', $certificate );
                                    $zip->addFromString( $certificateName, file_get_contents( $certificate ) );
                                }
                                $zip->close();
                                $arrCertificates = array( SSL_ZEN_DIR . 'keys/certificates.zip' );
                            } else {
                                $arrCertificates = array(
                                    SSL_ZEN_DIR . 'keys/privatekey.txt',
                                    SSL_ZEN_DIR . 'keys/certificate.txt',
                                    SSL_ZEN_DIR . 'keys/fullchain.txt',
                                    SSL_ZEN_DIR . 'keys/cabundle.txt'
                                );
                            }
                            
                            //TODO move elsewhere
                            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
                            $message = __( 'Hello,', 'ssl-zen' ) . '<br><br>';
                            $message .= __( 'Thank you for using SSLZen.com for generating your SSL certificate.', 'ssl-zen' ) . '<br><br>';
                            $message .= __( 'Download the attached files on your local computer, You will need them in the next step to install SSL certificate on your website.', 'ssl-zen' ) . '<br>';
                            $message .= __( 'You can open these files using any text editors such as Notepad.', 'ssl-zen' ) . '<br><br>';
                            $message .= __( 'What does these files do?', 'ssl-zen' ) . '<br>';
                            $message .= __( 'privatekey.txt = Private Key: ( KEY )', 'ssl-zen' ) . '<br>';
                            $message .= __( 'certificate.txt = Certificate: ( CRT )', 'ssl-zen' ) . '<br>';
                            $message .= __( 'cabundle.txt = Certificate Authority Bundle: ( CABUNDLE )', 'ssl-zen' ) . '<br><br>';
                            $message .= __( 'Please return back to SSL Zen and complete the remaining steps.', 'ssl-zen' ) . '<br><br>';
                            $message .= __( 'Thanks,', 'ssl-zen' ) . '<br>';
                            $message .= __( 'SSL Zen', 'ssl-zen' );
                            wp_mail(
                                get_option( 'ssl_zen_email', '' ),
                                'Confidential: SSL Certificates for ' . get_option( 'ssl_zen_base_domain', '' ),
                                $message,
                                $headers,
                                $arrCertificates
                            );
                        }
                        
                        update_option( 'ssl_zen_settings_stage', 'step3' );
                        update_option( 'ssl_zen_certificate_60_days', date_i18n( 'Y-m-d', strtotime( "+60 day" ) ) );
                        update_option( 'ssl_zen_certificate_90_days', date_i18n( 'Y-m-d', strtotime( "+90 day" ) ) );
                        update_option( 'ssl_zen_certificate_60_days_email_sent', '' );
                        update_option( 'ssl_zen_certificate_90_days_email_sent', '' );
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step3&info=successfully_generated' ) );
                        die;
                    } else {
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=invalid_sub_step' ) );
                        die;
                    }
                
                } else {
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=invalid_sub_step' ) );
                    die;
                }
            
            } elseif ( isset( $installCertificateNonce ) && wp_verify_nonce( $installCertificateNonce, 'ssl_zen_install_certificate' ) ) {
                // Verify SSL
                $isValid = ssl_zen_certificate::verifyssl( get_option( 'ssl_zen_base_domain', '' ) );
                
                if ( $isValid ) {
                    update_option( 'ssl_zen_settings_stage', 'step4' );
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step4' ) );
                    die;
                } else {
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step3&info=error' ) );
                    die;
                }
            
            } elseif ( isset( $activateSslNonce ) && wp_verify_nonce( $activateSslNonce, 'ssl_zen_activate_ssl' ) ) {
                // Verify SSL
                $isValid = ssl_zen_certificate::verifyssl( get_option( 'ssl_zen_base_domain', '' ) );
                
                if ( $isValid ) {
                    $siteUrl = str_replace( "http://", "https://", get_option( 'siteurl' ) );
                    $homeUrl = str_replace( "http://", "https://", get_option( 'home' ) );
                    update_option( 'siteurl', $siteUrl );
                    update_option( 'home', $homeUrl );
                    update_option( 'ssl_zen_ssl_activated', '1' );
                    update_option( 'ssl_zen_ssl_activated_date', time() );
                    update_option( 'ssl_zen_settings_stage', 'review' );
                    // Remove http verification files, no meter what variant have used before
                    ssl_zen_helper::deleteAll( ABSPATH . '.well-known/acme-challenge', true );
                    // Update acme-challenge htaccess to force https
                    self::createHtaccessForWellKnown();
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=review' ) );
                    die;
                } else {
                    $siteUrl = str_replace( "https://", "http://", get_option( 'siteurl' ) );
                    $homeUrl = str_replace( "https://", "http://", get_option( 'home' ) );
                    update_option( 'siteurl', $siteUrl );
                    update_option( 'home', $homeUrl );
                    update_option( 'ssl_zen_ssl_activated', '' );
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step4&info=error' ) );
                    die;
                }
            
            } elseif ( isset( $settingsNonce ) && wp_verify_nonce( $settingsNonce, 'ssl_zen_settings' ) ) {
                
                if ( !empty($_POST['ssl_zen_deactivate_plugin']) ) {
                    self::remove_plugin();
                    wp_redirect( admin_url( 'plugins.php' ) );
                    exit;
                } elseif ( !empty($_POST['ssl_zen_renew_certificate']) ) {
                    // Renew click handle. We avoid checks about valid renew date
                    // If the post data is not empty then the renew is valid
                    update_option( 'ssl_zen_settings_stage', 'step1' );
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step1' ) );
                    die;
                } else {
                    
                    if ( sz_fs()->is_plan( 'cdn', true ) ) {
                        self::updateStackpathSettings();
                    } else {
                        $htaccessRedirect = ( isset( $_POST['enable_301_htaccess_redirect'] ) ? '1' : '0' );
                        $htaccessLock = ( isset( $_POST['lock_htaccess_file'] ) ? '1' : '0' );
                        update_option( 'ssl_zen_enable_301_htaccess_redirect', $htaccessRedirect );
                        update_option( 'ssl_zen_lock_htaccess_file', $htaccessLock );
                        $info = 'success_settings';
                        $hasHtaccessRules = self::check_htaccess_rules();
                        if ( $htaccessRedirect == '1' && $hasHtaccessRules === false || $htaccessRedirect == '0' && $hasHtaccessRules === true ) {
                            
                            if ( $htaccessLock ) {
                                $info = 'lock';
                            } else {
                                // Make sure htaccess is writable
                                
                                if ( is_writable( ABSPATH . '.htaccess' ) ) {
                                    $htaccess = file_get_contents( ABSPATH . '.htaccess' );
                                    
                                    if ( $htaccessRedirect == '1' ) {
                                        // Add rules to htaccess
                                        $rules = self::get_htaccess_rules();
                                        // insert rules before wordpress part.
                                        
                                        if ( strlen( $rules ) > 0 ) {
                                            $wptag = "# BEGIN WordPress";
                                            
                                            if ( strpos( $htaccess, $wptag ) !== false ) {
                                                $htaccess = str_replace( $wptag, $rules . $wptag, $htaccess );
                                            } else {
                                                $htaccess = $htaccess . $rules;
                                            }
                                            
                                            insert_with_markers( ABSPATH . '.htaccess', 'SSL_ZEN', $htaccess );
                                        }
                                    
                                    } else {
                                        // Remove rules from htaccess
                                        $pattern = "/#\\s?BEGIN\\s?SSL_ZEN.*?#\\s?END\\s?SSL_ZEN/s";
                                        
                                        if ( preg_match( $pattern, $htaccess ) ) {
                                            $htaccess = preg_replace( $pattern, "", $htaccess );
                                            insert_with_markers( ABSPATH . '.htaccess', '', $htaccess );
                                        }
                                    
                                    }
                                
                                } else {
                                    $info = 'writeerr';
                                }
                            
                            }
                        
                        }
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=settings&info=' . $info ) );
                        die;
                    }
                
                }
            
            }
            
            if ( isset( $_REQUEST['page'] ) ) {
                $page = sanitize_text_field( $_REQUEST['page'] );
            }
            if ( isset( $_REQUEST['tab'] ) ) {
                $tab = trim( sanitize_text_field( $_REQUEST['tab'] ) );
            }
            
            if ( isset( $page ) && $page == 'ssl_zen' ) {
                
                if ( isset( $_SERVER['HTTP_CF_RAY'] ) && $tab !== 'cloudflare_detected_state' ) {
                    update_option( 'ssl_zen_settings_stage', 'cloudflare_detected_state' );
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=cloudflare_detected_state' ) );
                    exit;
                }
                
                
                if ( (stripos( ssl_zen_helper::getDomain(), '.temp.domains' ) !== false || stripos( ssl_zen_helper::getDomain(), '.temp.domain' ) !== false) && $tab !== 'bluehost_detected_state' ) {
                    update_option( 'ssl_zen_settings_stage', 'bluehost_detected_state' );
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=bluehost_detected_state' ) );
                    exit;
                }
                
                if ( ssl_zen_helper::checkCPanelAvailabilityOfCurrentSite() ) {
                    // Step 2 should be automated for both cpanel free and pro
                    if ( !empty($tab) && $tab == 'step2' ) {
                        
                        if ( !isset( $_REQUEST['info'] ) && !isset( $_REQUEST['certificate_files'] ) && !isset( $_REQUEST['install'] ) ) {
                            self::verify_cpanel_cred();
                            $exists_ceritifacate_files = self::check_certificate_files();
                            
                            if ( $exists_ceritifacate_files == true ) {
                                update_option( 'ssl_zen_settings_stage', 'step3' );
                                wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step3' ) );
                            } else {
                                self::generate_acme_files_and_verify();
                                update_option( 'ssl_zen_settings_stage', 'step3' );
                                wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step3' ) );
                            }
                        
                        }
                    
                    }
                }
                /* Check if correct tab is loaded else redirect to the correct tab */
                $currentSettingTab = get_option( 'ssl_zen_settings_stage', '' );
                
                if ( $currentSettingTab != '' && !isset( $tab ) ) {
                    
                    if ( $currentSettingTab == 'settings' ) {
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=renew' ) );
                        exit;
                    } else {
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=' . $currentSettingTab ) );
                        exit;
                    }
                
                } else {
                    $tab = ( isset( $tab ) ? $tab : '' );
                    
                    if ( $currentSettingTab != $tab && !in_array( $tab, self::$allowedTabs[$currentSettingTab] ) ) {
                        $url = 'admin.php?page=ssl_zen';
                        if ( $currentSettingTab != '' ) {
                            $url .= '&tab=' . $currentSettingTab;
                        }
                        wp_redirect( admin_url( $url ) );
                        exit;
                    }
                    
                    // The initial point
                    
                    if ( $currentSettingTab == '' ) {
                        // Check if website is installed locally
                        
                        if ( ssl_zen_helper::checkIfWebsiteInstalledLocally() ) {
                            // Set stage and redirect
                            update_option( 'ssl_zen_settings_stage', 'error_state' );
                            wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=error_state' ) );
                            exit;
                        }
                        
                        // Check system requirements
                        update_option( 'ssl_zen_settings_stage', 'system_requirements' );
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=system_requirements' ) );
                        exit;
                    }
                
                }
                
                // Executes when the user clicks to download domain authorization files.
                if ( isset( $_REQUEST['download'] ) ) {
                    $download = trim( sanitize_text_field( $_REQUEST['download'] ) );
                }
                
                if ( isset( $download ) && $download != '' ) {
                    
                    if ( is_numeric( $download ) && $currentSettingTab == 'step2' ) {
                        $arrPending = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_HTTP );
                        // This is related to step2 verification files download
                        
                        if ( isset( $arrPending[$download] ) && is_array( $arrPending[$download] ) ) {
                            $fileName = ( isset( $arrPending[$download]['filename'] ) ? $arrPending[$download]['filename'] : '' );
                            $fileContent = ( isset( $arrPending[$download]['content'] ) ? $arrPending[$download]['content'] : '' );
                        } else {
                            wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=dlerr' ) );
                            exit;
                        }
                    
                    } elseif ( $currentSettingTab == 'step3' ) {
                        // This is related to step3 certs files download
                        $fileName = ( $download === 'privatekey' ? $download . '.pem' : $download . '.crt' );
                        
                        if ( file_exists( SSL_ZEN_DIR . 'keys/' . $fileName ) ) {
                            $fileContent = file_get_contents( SSL_ZEN_DIR . 'keys/' . $fileName );
                        } else {
                            wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step3&info=dlerr' ) );
                            exit;
                        }
                    
                    } elseif ( $currentSettingTab == 'review' ) {
                        // This is related to debug-log status-info download
                        
                        if ( $download == 'status_info' ) {
                            $fileName = 'status_info.csv';
                            // File pointer connected with output stream
                            $file = fopen( 'php://output', 'w' );
                            // Get fields
                            $infoFields = array_merge( ssl_zen_helper::getServerStatusFields(), ssl_zen_helper::getWordPressStatusFields() );
                            // Start buffering, because here we are proceeding the output
                            ob_start();
                            // Set file columns
                            fputcsv( $file, array( 'Property', 'Value' ) );
                            // Set content
                            foreach ( $infoFields as $key => $field ) {
                                fputcsv( $file, [ $key, $field ] );
                            }
                            // Read to string, by get from buffer and cleaning it
                            $fileContent = ob_get_clean();
                        } elseif ( $download == 'debug_log' ) {
                            $fileName = 'debug.log';
                            $fileContent = ( file_exists( SSL_ZEN_DIR . 'log/debug.log' ) ? file_get_contents( SSL_ZEN_DIR . 'log/' . $fileName ) : '' );
                        } else {
                            wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=settings&info=dlerr_general' ) );
                            exit;
                        }
                    
                    }
                    
                    header( 'Content-Type: text/plain' );
                    header( 'Content-Disposition: attachment; filename=' . $fileName );
                    header( 'Expires: 0' );
                    header( 'Cache-Control: must-revalidate' );
                    header( 'Pragma: public' );
                    header( 'Content-Length: ' . strlen( $fileContent ) );
                    echo  $fileContent ;
                    die;
                }
            
            }
        
        }
        
        /**
         * Functon to check if htaccess rules exists
         *
         * @since 1.0
         * @static
         */
        public static function check_htaccess_rules()
        {
            
            if ( file_exists( ABSPATH . '.htaccess' ) && is_readable( ABSPATH . '.htaccess' ) ) {
                $htaccess = file_get_contents( ABSPATH . '.htaccess' );
                $check = null;
                preg_match( "/BEGIN\\s?SSL_ZEN/", $htaccess, $check );
                
                if ( count( $check ) === 0 ) {
                    return false;
                } else {
                    return true;
                }
            
            }
            
            return false;
        }
        
        /**
         * Functon to get all the htaccess rules
         *
         * @since 1.0
         * @static
         */
        public static function get_htaccess_rules()
        {
            $rule = "";
            $response = wp_remote_get( home_url() );
            $filecontents = '';
            if ( is_array( $response ) ) {
                $filecontents = wp_remote_retrieve_body( $response );
            }
            //if the htaccess test was successfull, and we know the redirectype, edit
            $rule .= "<IfModule mod_rewrite.c>" . "\n";
            $rule .= "RewriteEngine on" . "\n";
            $or = "";
            
            if ( strpos( $filecontents, "#SERVER-HTTPS-ON#" ) !== false || isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) == 'on' ) {
                $rule .= "RewriteCond %{HTTPS} !=on [NC]" . "\n";
            } elseif ( strpos( $filecontents, "#SERVER-HTTPS-1#" ) !== false || isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) == '1' ) {
                $rule .= "RewriteCond %{HTTPS} !=1" . "\n";
            } elseif ( strpos( $filecontents, "#LOADBALANCER#" ) !== false || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
                $rule .= "RewriteCond %{HTTP:X-Forwarded-Proto} !https" . "\n";
            } elseif ( strpos( $filecontents, "#HTTP_X_PROTO#" ) !== false || isset( $_SERVER['HTTP_X_PROTO'] ) && $_SERVER['HTTP_X_PROTO'] == 'SSL' ) {
                $rule .= "RewriteCond %{HTTP:X-Proto} !SSL" . "\n";
            } elseif ( strpos( $filecontents, "#CLOUDFLARE#" ) !== false || isset( $_SERVER['HTTP_CF_VISITOR'] ) && $_SERVER['HTTP_CF_VISITOR'] == 'https' ) {
                $rule .= "RewriteCond %{HTTP:CF-Visitor} '" . '"scheme":"http"' . "'" . "\n";
                //some concatenation to get the quotes right.
            } elseif ( strpos( $filecontents, "#SERVERPORT443#" ) !== false || isset( $_SERVER['SERVER_PORT'] ) && '443' == $_SERVER['SERVER_PORT'] ) {
                $rule .= "RewriteCond %{SERVER_PORT} !443" . "\n";
            } elseif ( strpos( $filecontents, "#CLOUDFRONT#" ) !== false || isset( $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] ) && $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] == 'https' ) {
                $rule .= "RewriteCond %{HTTP:CloudFront-Forwarded-Proto} !https" . "\n";
            } elseif ( strpos( $filecontents, "#HTTP_X_FORWARDED_SSL_ON#" ) !== false || isset( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) {
                $rule .= "RewriteCond %{HTTP:X-Forwarded-SSL} !on" . "\n";
            } elseif ( strpos( $filecontents, "#HTTP_X_FORWARDED_SSL_1#" ) !== false || isset( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == '1' ) {
                $rule .= "RewriteCond %{HTTP:X-Forwarded-SSL} !=1" . "\n";
            } elseif ( strpos( $filecontents, "#ENVHTTPS#" ) !== false || isset( $_ENV['HTTPS'] ) && 'on' == $_ENV['HTTPS'] ) {
                $rule .= "RewriteCond %{ENV:HTTPS} !=on" . "\n";
            }
            
            //if multisite, and NOT subfolder install (checked for in the detect_config function)
            //, add a condition so it only applies to sites where plugin is activated
            
            if ( is_multisite() ) {
                global  $wp_version ;
                $sites = ( $wp_version >= 4.6 ? get_sites() : wp_get_sites() );
                foreach ( $sites as $domain ) {
                    //remove http or https.
                    $domain = preg_replace( "/(http:\\/\\/|https:\\/\\/)/", "", $domain );
                    //We excluded subfolders, so treat as domain
                    $domain_no_www = str_replace( "www.", "", $domain );
                    $domain_yes_www = "www." . $domain_no_www;
                    $rule .= "#rewritecond " . $domain . "\n";
                    $rule .= "RewriteCond %{HTTP_HOST} ^" . preg_quote( $domain_no_www, "/" ) . " [OR]" . "\n";
                    $rule .= "RewriteCond %{HTTP_HOST} ^" . preg_quote( $domain_yes_www, "/" ) . " [OR]" . "\n";
                    $rule .= "#end rewritecond " . $domain . "\n";
                }
                //now remove last [OR] if at least on one site the plugin was activated, so we have at lease one condition
                if ( count( $sites ) > 0 ) {
                    $rule = strrev( implode( "", explode( strrev( "[OR]" ), strrev( $rule ), 2 ) ) );
                }
            }
            
            //fastest cache compatibility
            if ( class_exists( 'WpFastestCache' ) ) {
                $rule .= "RewriteCond %{REQUEST_URI} !wp-content\\/cache\\/(all|wpfc-mobile-cache)" . "\n";
            }
            //Exclude .well-known/acme-challenge for Let's Encrypt validation
            $rule .= "RewriteCond %{REQUEST_URI} !^/\\.well-known/acme-challenge/" . "\n";
            $rule .= "RewriteRule ^(.*)\$ https://%{HTTP_HOST}/\$1 [R=301,L]" . "\n";
            $rule .= "</IfModule>" . "\n";
            if ( strlen( $rule ) > 0 ) {
                $rule = "\n" . "# BEGIN SSL_ZEN\n" . $rule . "# END SSL_ZEN" . "\n";
            }
            $rule = preg_replace( "/\n+/", "\n", $rule );
            return $rule;
        }
        
        /**
         * Functon to remove a plugin from the array
         *
         * @since 1.0
         * @static
         */
        private static function remove_plugin_from_array( $plugin, $activePlugins )
        {
            $key = array_search( $plugin, $activePlugins );
            if ( false !== $key ) {
                unset( $activePlugins[$key] );
            }
            return $activePlugins;
        }
        
        /**
         * Functon to remove a plugin from active plugins list
         *
         * @since 1.0
         * @static
         */
        private static function remove_plugin()
        {
            
            if ( is_multisite() ) {
                $activePlugins = get_site_option( 'active_sitewide_plugins', array() );
                if ( is_plugin_active_for_network( SSL_ZEN_BASEFILE ) ) {
                    unset( $activePlugins[SSL_ZEN_BASEFILE] );
                }
                update_site_option( 'active_sitewide_plugins', $activePlugins );
                /* remove plugin one by one on each site */
                $sites = self::get_network_sites();
                foreach ( $sites as $site ) {
                    self::switch_network_site( $site );
                    $activePlugins = get_option( 'active_plugins', array() );
                    $activePlugins = self::remove_plugin_from_array( SSL_ZEN_BASEFILE, $activePlugins );
                    update_option( 'active_plugins', $activePlugins );
                    /* switches back to previous blog, not current, so we have to do it each loop */
                    restore_current_blog();
                }
            } else {
                $activePlugins = get_option( 'active_plugins', array() );
                $activePlugins = self::remove_plugin_from_array( SSL_ZEN_BASEFILE, $activePlugins );
                update_option( 'active_plugins', $activePlugins );
            }
            
            self::remove_stackpath();
        }
        
        /**
         * Function to get all network sites
         *
         * @since 1.0
         * @static
         */
        private static function get_network_sites()
        {
            global  $wp_version ;
            $sites = ( $wp_version >= 4.6 ? get_sites() : wp_get_sites() );
            return $sites;
        }
        
        /**
         * Functon to switch to network sites
         *
         * @since 1.0
         * @static
         */
        private static function switch_network_site( $site )
        {
            global  $wp_version ;
            
            if ( $wp_version >= 4.6 ) {
                switch_to_blog( $site->blog_id );
            } else {
                switch_to_blog( $site['blog_id'] );
            }
        
        }
        
        /**
         * Hook to remove all the plugin settings on deactivation
         *
         * @since 1.0
         * @static
         */
        public static function deactivate_plugin()
        {
            
            if ( is_multisite() ) {
                // @TODO: this is wrong - this should be done in uninstall.
                delete_site_option( 'ssl_zen_settings_stage' );
                delete_site_option( 'ssl_zen_include_wwww' );
                delete_site_option( 'ssl_zen_domains' );
                delete_site_option( 'ssl_zen_base_domain' );
                delete_site_option( 'ssl_zen_email' );
                delete_site_option( 'ssl_zen_certificate_60_days' );
                delete_site_option( 'ssl_zen_certificate_90_days' );
                delete_site_option( 'ssl_zen_certificate_60_days_email_sent' );
                delete_site_option( 'ssl_zen_certificate_90_days_email_sent' );
                delete_site_option( 'ssl_zen_ssl_activated' );
                delete_site_option( 'ssl_zen_ssl_activated_date' );
                delete_site_option( 'ssl_zen_enable_301_htaccess_redirect' );
                delete_site_option( 'ssl_zen_lock_htaccess_file' );
                delete_site_option( 'ssl_zen_ssl_check_status' );
                delete_site_option( 'ssl_zen_domain_verification_variant' );
                delete_site_option( 'ssl_zen_dns_check_activation' );
                delete_site_option( 'ssl_zen_enable_debug' );
                delete_site_option( 'ssl_zen_cpanel_detected' );
                $sites = self::get_network_sites();
                foreach ( $sites as $site ) {
                    self::switch_network_site( $site );
                    $siteUrl = str_replace( "https://", "http://", get_option( 'siteurl', '' ) );
                    $homeUrl = str_replace( "https://", "http://", get_option( 'home', '' ) );
                    update_option( 'siteurl', $siteUrl );
                    update_option( 'home', $homeUrl );
                    // @TODO: this is wrong - this should be done in uninstall.
                    delete_option( 'ssl_zen_settings_stage' );
                    delete_option( 'ssl_zen_include_wwww' );
                    delete_option( 'ssl_zen_domains' );
                    delete_option( 'ssl_zen_base_domain' );
                    delete_option( 'ssl_zen_email' );
                    delete_option( 'ssl_zen_certificate_60_days' );
                    delete_option( 'ssl_zen_certificate_90_days' );
                    delete_option( 'ssl_zen_certificate_60_days_email_sent' );
                    delete_option( 'ssl_zen_certificate_90_days_email_sent' );
                    delete_option( 'ssl_zen_ssl_activated' );
                    delete_option( 'ssl_zen_ssl_activated_date' );
                    delete_option( 'ssl_zen_enable_301_htaccess_redirect' );
                    delete_option( 'ssl_zen_lock_htaccess_file' );
                    delete_option( 'ssl_zen_ssl_check_status' );
                    delete_option( 'ssl_zen_domain_verification_variant' );
                    delete_option( 'ssl_zen_dns_check_activation' );
                    delete_option( 'ssl_zen_enable_debug' );
                    delete_option( 'ssl_zen_cpanel_detected' );
                    restore_current_blog();
                }
            } else {
                /* Remove SSL from site and home urls */
                $siteUrl = str_replace( "https://", "http://", get_option( 'siteurl', '' ) );
                $homeUrl = str_replace( "https://", "http://", get_option( 'home', '' ) );
                update_option( 'siteurl', $siteUrl );
                update_option( 'home', $homeUrl );
                // @TODO: this is wrong - this should be done in uninstall.
                /* Remove all the database settings */
                delete_option( 'ssl_zen_settings_stage' );
                delete_option( 'ssl_zen_include_wwww' );
                delete_option( 'ssl_zen_domains' );
                delete_option( 'ssl_zen_base_domain' );
                delete_option( 'ssl_zen_email' );
                delete_option( 'ssl_zen_certificate_60_days' );
                delete_option( 'ssl_zen_certificate_90_days' );
                delete_option( 'ssl_zen_certificate_60_days_email_sent' );
                delete_option( 'ssl_zen_certificate_90_days_email_sent' );
                delete_option( 'ssl_zen_ssl_activated' );
                delete_option( 'ssl_zen_ssl_activated_date' );
                delete_option( 'ssl_zen_enable_301_htaccess_redirect' );
                delete_option( 'ssl_zen_lock_htaccess_file' );
                delete_option( 'ssl_zen_ssl_check_status' );
                delete_option( 'ssl_zen_domain_verification_variant' );
                delete_option( 'ssl_zen_dns_check_activation' );
                delete_option( 'ssl_zen_enable_debug' );
                delete_option( 'ssl_zen_activated' );
                delete_option( 'ssl_zen_activated_date' );
                delete_option( 'ssl_zen_cpanel_detected' );
                // this will help in firing reactivation.
                add_option( 'ssl_zen_deactivated', 1 );
                self::remove_fix_wp_config();
            }
            
            if ( !sz_fs()->is_plan( 'cdn', true ) ) {
                /* Remove rules from .htaccess file */
                
                if ( is_writable( ABSPATH . '.htaccess' ) ) {
                    $htaccess = file_get_contents( ABSPATH . '.htaccess' );
                    /* Remove rules from htaccess */
                    $pattern = "/#\\s?BEGIN\\s?SSL_ZEN.*?#\\s?END\\s?SSL_ZEN/s";
                    if ( preg_match( $pattern, $htaccess ) ) {
                        $htaccess = preg_replace( $pattern, "", $htaccess );
                    }
                    insert_with_markers( ABSPATH . '.htaccess', '', $htaccess );
                }
            
            }
            self::remove_plugin();
            // TODO check this
            // Added by Freemius to fix the 'Auto Install after payment' bug.
            
            if ( empty($_POST['action']) || $_POST['action'] !== sz_fs()->get_ajax_action( 'install_premium_version' ) ) {
                wp_redirect( admin_url( 'plugins.php?deactivate=true', 'http' ) );
                exit;
            }
        
        }
        
        /**
         * Hook to add custom links on the plugins page
         *
         * @param array $links
         *
         * @return array $links
         * @since 1.0
         * @static
         *
         */
        public static function plugin_action_links( $links )
        {
            if ( sz_fs()->is_plan( 'pro', true ) ) {
                $links[] = '<a href="' . admin_url( 'admin.php?page=ssl_zen&tab=step1' ) . '">' . __( 'Setup', 'ssl-zen' ) . '</a>';
            }
            $links[] = '<a href="' . admin_url( 'admin.php?page=ssl_zen&tab=settings' ) . '">' . __( 'Settings', 'ssl-zen' ) . '</a>';
            $links[] = '<a href="' . admin_url( 'admin.php?page=ssl_zen-contact' ) . '">' . __( 'Support', 'ssl-zen' ) . '</a>';
            return $links;
        }
        
        /**
         * Function to check is certificate files are exist
         *
         * @since 1.2
         * @static
         */
        private static function check_certificate_files()
        {
            if ( !file_exists( SSL_ZEN_DIR . 'keys/privatekey.txt' ) ) {
                return false;
            }
            if ( !file_exists( SSL_ZEN_DIR . 'keys/certificate.txt' ) ) {
                return false;
            }
            if ( !file_exists( SSL_ZEN_DIR . 'keys/fullchain.txt' ) ) {
                return false;
            }
            if ( !file_exists( SSL_ZEN_DIR . 'keys/cabundle.txt' ) ) {
                return false;
            }
            return true;
        }
        
        /**
         * Function to generate acme files for validate domain in lets encrypt
         *
         * @param bool $is_ajax
         *
         * @return mixed
         * @since 1.2
         * @static
         *
         */
        private static function generate_acme_files_and_verify( $is_ajax = false )
        {
            $wp_root = get_home_path();
            $directory = $wp_root . '.well-known/acme-challenge';
            $tokenPathRoot = $wp_root . '.well-known/acme-challenge/';
            $host = 'http://' . get_option( 'ssl_zen_base_domain', '' );
            if ( !file_exists( $directory ) && !mkdir( $directory, 0755, true ) ) {
                
                if ( $is_ajax == true ) {
                    return [
                        'status' => false,
                        'msg'    => 'directory_permission',
                    ];
                } else {
                    $info = 'directory_permission';
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=' . $info ) );
                }
            
            }
            $fileContentInitial = '';
            $uriInitial = '';
            $siteUriInitial = '';
            $path = '';
            $arrPending = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_HTTP );
            
            if ( $arrPending != false ) {
                for ( $i = 0 ;  $i < sizeof( $arrPending ) ;  $i++ ) {
                    if ( !isset( $arrPending[$i]['filename'] ) || empty($arrPending[$i]['filename']) ) {
                        throw new \RuntimeException( "Pending file name empty" );
                    }
                    if ( !isset( $arrPending[$i]['content'] ) || empty($arrPending[$i]['content']) ) {
                        throw new \RuntimeException( "Pending file content empty" );
                    }
                    $fileName = $arrPending[$i]['filename'];
                    $fileContent = $arrPending[$i]['content'];
                    
                    if ( $i == 0 ) {
                        $tokenPathInitial = $tokenPathRoot . $fileName;
                        $path = "/.well-known/acme-challenge/{$fileName}";
                        $uriInitial = $host . $path;
                        $siteUriInitial = get_site_url() . $path;
                        $fileContentInitial = $fileContent;
                    }
                    
                    if ( !file_put_contents( $tokenPathRoot . $fileName, $fileContent ) ) {
                        
                        if ( $is_ajax == true ) {
                            return [
                                'status' => false,
                                'msg'    => 'directory_permission2',
                            ];
                        } else {
                            $info = 'directory_permission2';
                            wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=' . $info ) );
                        }
                    
                    }
                }
                $payload = $fileContentInitial;
                chmod( $tokenPathInitial, 0644 );
                $response = wp_remote_get( $uriInitial );
                $payloadFromUri = ( !empty($response) && !$response instanceof WP_Error && !empty($response['body']) ? $response['body'] : null );
                $error = false;
                // Check for base URI
                
                if ( $payload !== $payloadFromUri ) {
                    $error = true;
                    // Check for site url path
                    $response = wp_remote_get( $siteUriInitial );
                    $payloadFromUri = ( !empty($response) && !$response instanceof WP_Error && !empty($response['body']) ? $response['body'] : null );
                    // Update error status for 2nd check
                    $error = $payload !== $payloadFromUri;
                }
                
                if ( $error ) {
                    
                    if ( $is_ajax == true ) {
                        return [
                            'status' => false,
                            'msg'    => 'token_missmatch',
                        ];
                    } else {
                        $info = 'token_missmatch';
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=' . $info . '&uri=' . base64_encode( $uriInitial ) . '&host=' . base64_encode( $host ) ) );
                        die;
                    }
                
                }
                ssl_zen_certificate::updateAuthorizations( \LEClient\LEOrder::CHALLENGE_TYPE_HTTP );
                $isValid = ssl_zen_certificate::validateAuthorization();
                
                if ( $isValid ) {
                    /* Finalize the order for SSL Certificate */
                    ssl_zen_certificate::finalizeOrder();
                    /* Generate SSL Certificate */
                    $arrCertificates = ssl_zen_certificate::generateCertificate();
                    update_option( 'ssl_zen_settings_stage', 'step2' );
                    update_option( 'ssl_zen_certificate_60_days', date_i18n( 'Y-m-d', strtotime( "+60 day" ) ) );
                    update_option( 'ssl_zen_certificate_90_days', date_i18n( 'Y-m-d', strtotime( "+90 day" ) ) );
                    update_option( 'ssl_zen_certificate_60_days_email_sent', '' );
                    update_option( 'ssl_zen_certificate_90_days_email_sent', '' );
                    return true;
                } else {
                    
                    if ( $is_ajax == true ) {
                        return [
                            'status' => false,
                            'msg'    => 'not_all_authorizations_are_valid',
                        ];
                    } else {
                        $info = 'not_all_authorizations_are_valid';
                        wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=' . $info . '&uri=' . base64_encode( $uriInitial ) . '&host' . base64_encode( $host ) ) );
                        die;
                    }
                
                }
            
            }
            
            if ( is_ssl() ) {
                
                if ( $is_ajax == true ) {
                    return [
                        'status' => false,
                        'msg'    => 'not_all_authorizations_are_valid',
                    ];
                } else {
                    $info = 'already_https';
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=' . $info ) );
                    die;
                }
            
            }
        }
        
        /**
         * Function to check is username and password are correct for login to cpanel
         * This function should be available to both free and paid plans
         * @param string $username
         * @param string $password
         * @param bool $is_ajax
         *
         * @return bool
         * @since 1.2
         * @static
         *
         */
        private static function verify_cpanel_cred( $username = '', $password = '', $is_ajax = false )
        {
            // Check the possibility of shell_exec to avoid unnecessary checks
            // and marks the credentials as valid
            if ( ssl_zen_certificate::supportCPanelCommandLineApi() ) {
                return true;
            }
            
            if ( $is_ajax == true ) {
                $cpanel_username = $username;
                $cpanel_pasword = $password;
            } else {
                $cpanel_username = get_option( 'ssl_zen_cpanel_username' );
                $cpanel_pasword = get_option( 'ssl_zen_cpanel_password' );
            }
            
            // TODO note that below two lines area not nessecary, cause I am getting 'localhost' from the getCpanelHost function
            $base = get_option( 'ssl_zen_base_domain', '' );
            $host = ( is_ssl() ? 'https://' . $base : 'http://' . $base );
            $cpanel_host = LogMeIn::getCpanelHost( $host );
            
            if ( $is_ajax == true ) {
                if ( $cpanel_host == false ) {
                    return false;
                }
            } else {
                
                if ( $cpanel_host == false ) {
                    $info = 'cpanel_not_exist';
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step1&info=' . $info ) );
                    die;
                }
            
            }
            
            $cpanel = new cPanel( $cpanel_host, $cpanel_username, $cpanel_pasword );
            $check = $cpanel->checkConnection();
            
            if ( $is_ajax == true ) {
                
                if ( $check == false ) {
                    return false;
                } else {
                    return true;
                }
            
            } else {
                
                if ( $check == false ) {
                    $info = 'cpanel_cant_connect';
                    wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step1&info=' . $info ) );
                    die;
                }
            
            }
        
        }
        
        /**
         * Function to install generated certificate files to cPanel
         *
         * @param bool $is_ajax
         *
         * @return mixed
         * @since 1.2
         * @static
         */
        private static function install_certificate( $is_ajax = false )
        {
        }
        
        /**
         * Creating htaccess file for well-known folder in order to force https to it
         */
        public static function createHtaccessForWellKnown()
        {
            $acmeHtaccessFileDir = ABSPATH . '.well-known/acme-challenge/.htaccess';
            
            if ( !file_exists( $acmeHtaccessFileDir ) ) {
                $file = fopen( $acmeHtaccessFileDir, "w" );
            } else {
                $file = true;
            }
            
            
            if ( $file !== false && is_writable( $acmeHtaccessFileDir ) ) {
                $rule = "<IfModule mod_rewrite.c>" . "\n";
                $rule .= "RewriteEngine on" . "\n";
                $rule .= "RewriteCond %{HTTPS} =on [NC]" . "\n";
                $rule .= "RewriteRule ^(.*)\$ http://%{HTTP_HOST} [R=301,L]" . "\n";
                $rule .= "</IfModule>" . "\n";
                insert_with_markers( $acmeHtaccessFileDir, 'SSL_ZEN', $rule );
            }
        
        }
    
    }
    /**
     * Calling init function and activate hooks and filters.
     */
    ssl_zen_admin::init();
}
