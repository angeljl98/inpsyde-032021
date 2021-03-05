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

if ( !class_exists( 'ssl_zen_scheduled' ) ) {
    /**
     * Class to manage scheduled tasks
     */
    class ssl_zen_scheduled
    {
        /**
         * Add hooks and filters for the plugin
         *
         * @since 1.0
         * @static
         */
        public static function init()
        {
            $sent60daysEmail = get_option( 'ssl_zen_certificate_60_days_email_sent', '' );
            $sent90daysEmail = get_option( 'ssl_zen_certificate_90_days_email_sent', '' );
            /* Send reminder after 60 days */
            
            if ( get_option( 'ssl_zen_certificate_60_days', '' ) != '' ) {
                
                if ( $sent60daysEmail == '1' ) {
                    /* If email already sent, remove from schedule */
                    $timestamp = wp_next_scheduled( 'ssl_zen_60_days_email' );
                    if ( $timestamp != false ) {
                        wp_unschedule_event( $timestamp, 'ssl_zen_60_days_email' );
                    }
                } else {
                    /* If email not sent schedule */
                    if ( !wp_next_scheduled( 'ssl_zen_60_days_email' ) ) {
                        wp_schedule_event( time(), 'daily', 'ssl_zen_60_days_email' );
                    }
                }
                
                add_action( 'ssl_zen_60_days_email', __CLASS__ . '::ssl_zen_60_days_email_hook' );
            }
            
            /* Send reminder after 90 days */
            
            if ( get_option( 'ssl_zen_certificate_90_days', '' ) != '' ) {
                
                if ( $sent90daysEmail == '1' ) {
                    /* If email already sent, remove from schedule */
                    $timestamp = wp_next_scheduled( 'ssl_zen_90_days_email' );
                    if ( $timestamp != false ) {
                        wp_unschedule_event( $timestamp, 'ssl_zen_90_days_email' );
                    }
                } else {
                    /* If email not sent schedule */
                    if ( !wp_next_scheduled( 'ssl_zen_90_days_email' ) ) {
                        wp_schedule_event( time(), 'daily', 'ssl_zen_90_days_email' );
                    }
                }
                
                add_action( 'ssl_zen_90_days_email', __CLASS__ . '::ssl_zen_90_days_email_hook' );
            }
        
        }
        
        /**
         * Function to send the renewal reminder email after 60 days
         *
         * @since 1.0
         * @static
         */
        public static function ssl_zen_60_days_email_hook()
        {
            
            if ( date_i18n( 'Y-m-d' ) > get_option( 'ssl_zen_certificate_60_days', '' ) ) {
                $headers = array( 'Content-Type: text/html; charset=UTF-8' );
                $message = __( 'Hello,', 'ssl-zen' ) . '<br><br>';
                $message .= __( 'Your SSL Certificate will expire on .', 'ssl-zen' ) . ' ' . get_option( 'ssl_zen_certificate_90_days', '' ) . '<br>';
                $message .= __( 'Please make sure to renew your certificate before then, or visitors to your website will encounter errors.', 'ssl-zen' ) . '<br><br>';
                $message .= __( 'If you want us to automatically renew your SSL certificates, please upgrade to <a href="https://checkout.freemius.com/mode/dialog/plugin/4586/plan/7397/">SSL Zen Pro.</a>', 'ssl-zen' ) . '<br>';
                $message .= __( 'Regards,', 'ssl-zen' ) . '<br>' . get_bloginfo( 'name' );
                wp_mail(
                    get_option( 'ssl_zen_email', '' ),
                    'Important: SSL certificate renewal reminder',
                    $message,
                    $headers
                );
                update_option( 'ssl_zen_certificate_60_days_email_sent', '1' );
            }
        
        }
        
        /**
         * Function to send the renewal reminder email after 90 days
         *
         * @since 1.0
         * @static
         */
        public static function ssl_zen_90_days_email_hook()
        {
            
            if ( date_i18n( 'Y-m-d' ) > get_option( 'ssl_zen_certificate_90_days', '' ) ) {
                $headers = array( 'Content-Type: text/html; charset=UTF-8' );
                $message = __( 'Hello,', 'ssl-zen' ) . '<br><br>';
                $message .= __( 'Your SSL Certificate expired on .', 'ssl-zen' ) . ' ' . get_option( 'ssl_zen_certificate_90_days', '' ) . '<br>';
                $message .= __( 'Please make sure to renew your certificate immediately.', 'ssl-zen' ) . '<br><br>';
                $message .= __( 'If you want us to automatically renew your SSL certificates, please upgrade to <a href="https://checkout.freemius.com/mode/dialog/plugin/4586/plan/7397/">SSL Zen Pro.</a>', 'ssl-zen' ) . '<br>';
                $message .= __( 'Regards,', 'ssl-zen' ) . '<br>' . get_bloginfo( 'name' );
                wp_mail(
                    get_option( 'ssl_zen_email', '' ),
                    'Urgent and Important: SSL certificate expired',
                    $message,
                    $headers
                );
                update_option( 'ssl_zen_certificate_90_days_email_sent', '1' );
            }
        
        }
    
    }
    /**
     * Calling init function and activate hooks and filters.
     */
    ssl_zen_scheduled::init();
}
