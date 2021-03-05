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

use LEClient\LEClient;
use LEClient\LEOrder;

if (!class_exists('ssl_zen_certificate')) {
    /**
     * Class to manage ssl certificates by interacting with LEClient library
     */
    class ssl_zen_certificate
    {
        /**
         * Create client on Let's Encrypt
         *
         * @param bool $redirect
         *
         * @return LEClient Returns the object of the Let's Encrypt Client
         * @since 1.0
         * @static
         */
        public static function createClient($redirect = true)
        {

            try {
                $email = get_option('ssl_zen_email', '');

                // Check the log flag
                if (!empty(get_option('ssl_zen_enable_debug', ''))) {
                    $log = LEClient::LOG_DEBUG;
                } else {
                    $log = LEClient::LOG_STATUS;
                }

                return new LEClient(array($email), LEClient::LE_PRODUCTION, $log, SSL_ZEN_DIR . 'keys');
            } catch (Exception $e) {
                if ($redirect) {
                    self::redirect_on_error($e);
                } else {
                    return null;
                }
            }
        }

        /**
         * Generates an order on Let's Encrypt
         *
         * @param bool $redirect
         *
         * @return LEOrder Returns the object of the Let's Encrypt Order
         * @since 1.0
         * @static
         */
        public static function generateOrder($redirect = true)
        {

            try {
                $baseDomainName = get_option('ssl_zen_base_domain', '');
                $domains = get_option('ssl_zen_domains', array());
                $client = self::createClient($redirect);

                if (!empty($client)) {
                    return $client->getOrCreateOrder($baseDomainName, $domains);
                } else {
                    return null;
                }
            } catch (Exception $e) {
                if ($redirect) {
                    self::redirect_on_error($e);
                } else {
                    return null;
                }
            }
        }

        /**
         * Checks for all the pending authorizations on Let's Encrypt for an order and
         * update the authorization status
         *
         * @param $type
         * @param bool $redirect
         *
         * @since 1.0
         * @static
         */
        public static function updateAuthorizations($type, $redirect = true)
        {
            try {
                $arrPending = self::getPendingAuthorization($type, $redirect);
                if (is_array($arrPending) && count($arrPending)) {
                    $order = self::generateOrder($redirect);
                    foreach ($arrPending as $pending) {
                        $order->verifyPendingOrderAuthorization($pending['identifier'], $type);
                    }
                }
            } catch (Exception $e) {
                if ($redirect) {
                    self::redirect_on_error($e);
                }
            }
        }

        /**
         * Check if the authorizations are valid for the particular order
         *
         * @param bool $redirect
         *
         * @return Boolean Returns the status of domain verification
         * @since 1.0
         * @static
         */
        public static function validateAuthorization($redirect = true)
        {
            try {
                $order = self::generateOrder($redirect);

                if (empty($order)) {
                    throw new Exception('Order is empty');
                }

                return $order->allAuthorizationsValid();
            } catch (Exception $e) {
                if ($redirect) {
                    self::redirect_on_error($e);
                } else {
                    return false;
                }
            }
        }

        /**
         * Fetches all the pending authorizations for the particular order
         *
         * @param $type
         *
         * @param bool $redirect
         *
         * @return array|object Returns all the pending authorizations
         * @since 1.0
         * @static
         */
        public static function getPendingAuthorization($type, $redirect = true)
        {
            try {
                $order = self::generateOrder($redirect);

                if (!empty($order)) {
                    return $order->getPendingAuthorizations($type);
                } else {
                    return null;
                }
            } catch (Exception $e) {
                if ($redirect) {
                    self::redirect_on_error($e);
                } else {
                    return [];
                }
            }
        }

        /**
         * Finalizes the Let's Encrypt order
         *
         * @since 1.0
         * @static
         */
        public static function finalizeOrder()
        {

            try {
                $order = ssl_zen_certificate::generateOrder();
                if (!$order->isFinalized()) {
                    $order->finalizeOrder();
                }
            } catch (Exception $e) {
                self::redirect_on_error($e);
            }
        }

        /**
         * Generates and returns the path in the form of array for the certificates for a particular order
         *
         * @return Array Paths of the certificates generated for a particular order
         * @since 1.0
         * @static
         */
        public static function generateCertificate()
        {

            try {
                $order = ssl_zen_certificate::generateOrder();
                if ($order->isFinalized()) {
                    $order->getCertificate();
                }

                return $order->getAllCertificates();
            } catch (Exception $e) {
                self::redirect_on_error($e);
            }
        }

        /**
         * Verifies if the SSL certificate is successfully installed on the domain or not.
         *
         * @return Bool True if the SSL certificate is installed successfully, false otherwise.
         * @since 1.0
         * @static
         */
        public static function verifyssl($domain)
        {
            $res = false;
            $stream = @stream_context_create(array('ssl' => array('capture_peer_cert' => true)));
            $socket = @stream_socket_client('ssl://' . $domain . ':443', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream);

            /* If we got a ssl certificate we check here, if the certificate domain */
            /* matches the website domain. */
            if ($socket) {

                $cont = stream_context_get_params($socket);

                $cert_ressource = $cont['options']['ssl']['peer_certificate'];
                $cert = openssl_x509_parse($cert_ressource);

                /* Expected name has format "/CN=*.yourdomain.com" */
                $namepart = explode('=', $cert['name']);

                /* We want to correctly confirm the certificate even */
                /* for subdomains like "www.yourdomain.com" */
                if (count($namepart) == 2) {
                    $cert_domain = trim($namepart[1], '*. ');
                    $check_domain = substr($domain, -strlen($cert_domain));
                    $res = ($cert_domain == $check_domain);
                }
            }

            return $res;
        }

        /**
         * Redirect user to the page when error is raised in the Lets Encrypt API
         *
         * @param $e Exception|null
         *
         * @since 1.1
         * @static
         */
        private static function redirect_on_error($e = null)
        {
            $currentSettingTab = get_option('ssl_zen_settings_stage', '');

            if ($currentSettingTab == '') {
                $currentSettingTab = 'step1';
            }

            wp_redirect(admin_url('admin.php?page=ssl_zen&tab=' . $currentSettingTab . '&info=api_error'));
            exit;
        }

        /**
         * Check support of shell_exec function
         * @return bool
         * @since 1.7
         */
        public static function supportShellExec()
        {
            return function_exists('shell_exec');
        }

        /**
         * Check support of cPanel command line api
         * @return bool
         * @since 1.7
         */
        public static function supportCPanelCommandLineApi()
        {
            return self::supportShellExec() && !empty(shell_exec('which uapi'));
        }

        /**
         * Install SSL certs via uapi command line
         *
         * @param $domain
         * @param $keysPath
         *
         * @return string|null
         * @since 1.7
         */
        public static function installSslViaUApiCommandline($domain, $keysPath)
        {
            // Define the SSL certificate and key files.
            $cert = urlencode(str_replace('\r\n', '\n', file_get_contents($keysPath . '/certificate.txt')));
            $key = urlencode(str_replace('\r\n', '\n', file_get_contents($keysPath . '/privatekey.txt')));
            $caBundle = urlencode(str_replace('\r\n', '\n', file_get_contents($keysPath . '/cabundle.txt')));

            return shell_exec("uapi SSL install_ssl domain=$domain cert=$cert key=$key cabundle=$caBundle");
        }


        /**
         * Check domain with let's debug
         *
         * @param $baseDomain
         * @param string $method
         *
         * @since 2.0.4
         */
        public static function debugLetsEncrypt($baseDomain, $method = LEOrder::CHALLENGE_TYPE_HTTP)
        {
            if (SSL_ZEN_PLUGIN_ALLOW_DEV) {
                return false;
            }

            $apiResponse = wp_remote_post('https://letsdebug.net', [
                'timeout' => '15',
                'sslverify' => false,
                'headers' => array(
                    'content-type' => 'application/json'
                ),
                'body' => json_encode([
                    'method' => $method,
                    'domain' => $baseDomain
                ])
            ]);

            if (!empty($apiResponse) && !is_wp_error($apiResponse)) {
                $bodyObj = !empty($apiResponse['body']) ? json_decode($apiResponse['body']) : null;
                $id = !empty($bodyObj) && !empty($bodyObj->ID) ? (int)$bodyObj->ID : null;
                // Fetch the id and
                if (!empty($id)) {

                    // Sleep in order to pass the processing status
                    sleep(10);

                    // Prepare get call
                    $apiResponse = wp_remote_get('https://letsdebug.net/' . $baseDomain . '/' . $id, [
                        'timeout' => '15',
                        'sslverify' => false,
                        'headers' => array(
                            'Accept' => 'application/json'
                        )
                    ]);

                    if (!empty($apiResponse) && !is_wp_error($apiResponse)) {
                        $bodyObj = !empty($apiResponse['body']) ? json_decode($apiResponse['body']) : null;
                        if (!empty($bodyObj->result) && !empty($bodyObj->result->problems)) {
                            $problems = $bodyObj->result->problems;
                            $error = false;
                            foreach ($problems as $problem) {
                                if ($problem->severity != 'debug') {
                                    // So the domain has problem, then redirect with error
                                    $error = true;
                                    break;
                                }
                            }

                            if ($error) {
                                $currentSettingTab = get_option('ssl_zen_settings_stage', '');
                                if ($currentSettingTab == '') {
                                    $currentSettingTab = 'step1';
                                }

                                wp_redirect(admin_url('admin.php?page=ssl_zen&tab=' . $currentSettingTab . '&info=lets_encrypt_error_' . strtolower($problem->name)));
                                exit;
                            }
                        }
                    }
                }
            }
        }
    }
}
