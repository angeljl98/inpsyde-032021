<?php

if (!class_exists('ssl_zen_helper')) {

    /**
     * Class to implement useful functionality across the plugin
     *
     * @since 2.0
     */
    class ssl_zen_helper
    {
        /**
         * StackPath IP addresses
         * @var string[]
         */
        public static $STACKPATH_IP = array( '151.139.128.10', '151.139.128.11' );

        /**
         * Couple minor steps to check if the website installed locally or the host name is IP address
         */
        public static function checkIfWebsiteInstalledLocally()
        {
            if (SSL_ZEN_PLUGIN_ALLOW_DEV) {
                return false;
            }
            $host = gethostname();
            $ip = gethostbyname($host);
            return in_array($ip, ['127.0.0.1', '::1']);
        }

        /**
         * Check if tab is available in current stage
         *
         * @param $currentTab
         * @param $tab
         * @param $allowedTabs
         *
         * @return bool
         */
        public static function isTabAvailableAtThisStage($currentTab, $tab, $allowedTabs)
        {
            return !empty($allowedTabs[$currentTab]) && is_array($allowedTabs[$currentTab]) && in_array($tab, $allowedTabs[$currentTab]);
        }

        /**
         * Get system requirements status
         */
        public static function getSystemRequirementsStatus()
        {
            return [
                'php' => version_compare(phpversion(), '5.6.2') !== -1,
                'curl' => function_exists('curl_version'),
                'openssl' => extension_loaded('openssl')
            ];
        }

        /**
         * Get cURL version if it is enabled
         *
         * @return array|string
         */
        public static function getCurlVersion()
        {
            if (function_exists('curl_version')) {
                $versionArr = curl_version();

                return $versionArr['version'];
            } else {
                return '-';
            }
        }

        /**
         * Get allow_url_fopen value
         *
         * @return string
         */
        public static function getAllowUrlFOpenActiveStatus()
        {
            return !empty(ini_get('allow_url_fopen')) ? 'On' : 'No';
        }

        /**
         * Get shell_exec availability
         *
         * @return string
         */
        public static function getShellExecStatus()
        {
            return function_exists('shell_exec') ? 'Available' : 'No';
        }

        /**
         * Get SSL installation status
         *
         * @return string
         */
        public static function getSslInstallationStatus()
        {
            if (!empty(ssl_zen_certificate::verifyssl(get_option('ssl_zen_base_domain', '')))) {
                return 'Successfully installed';
            }

            return 'No';
        }

        /**
         * Get SSL version
         *
         * @return string
         */
        public static function getSSLversion()
        {
            return !empty(OPENSSL_VERSION_TEXT) ? OPENSSL_VERSION_TEXT : '-';
        }

        /**
         * Check weather cUrl enabled
         */
        public static function getCurlActiveStatus()
        {
            return function_exists('curl_version') ? __('Enabled', 'ssl-zen') : 'No';
        }

        /**
         * Get current server version
         */
        public static function getServerVersion()
        {
            return !empty($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '-';
        }

        /**
         * Get cPanel version if is installed on the server
         */
        public static function getCPanelVersion()
        {
            return '-';
        }

        /**
         * Check weather to show steps
         *
         * @param $stage
         *
         * @return bool
         */
        public static function stageIsStep($stage)
        {
            return strpos($stage, 'step') !== false;
        }

        /**
         * @param $stage
         * @param $allowedTabs
         * @param $layout
         *
         * @return mixed
         */
        public static function showLayoutPart($stage, $allowedTabs, $layout)
        {
            return isset($allowedTabs[$stage]['layout']) ? $allowedTabs[$stage]['layout'][$layout] : false;
        }

        /**
         * Function to check the current site cPanel availability.
         *
         * @return bool
         */
        public static function checkCPanelAvailabilityOfCurrentSite()
        {
            $stored_status = get_option('ssl_zen_cpanel_detected');
            if($stored_status !== false) {
                return $stored_status == 'enabled';
            }
            $host = untrailingslashit(str_ireplace(array('https://', 'http://'), '', self::getDomain()));

            $url = is_ssl() ? "https://$host:2083" : "http://$host:2082";
            $response = wp_remote_get($url, [
                'headers' => [
                    'Connection' => 'close'
                ],
                'sslverify' => false
            ]);

            $status = !is_wp_error($response);
            update_option('ssl_zen_cpanel_detected', $status ? 'enabled' : 'disabled');
            return $status;
        }

        /**
         * Function to delete all the files and folders within a directory
         *
         * @param string $str
         * @param bool $root
         *
         * @since 1.0
         * @static
         */
        public static function deleteAll($str, $root = true)
        {
            // It it's a file.
            if (is_file($str)) {
                // Attempt to delete it.
                unlink($str);
            } // If it's a directory.
            elseif (is_dir($str)) {
                // Get a list of the files in this directory.
                $scan = glob(rtrim($str, '/') . '/*');

                // Loop through the list of files.
                foreach ($scan as $index => $path) {
                    // Call our recursive function.
                    self::deleteAll($path, false);
                    //Remove the directory itself.
                }

                if (!$root) {
                    @rmdir($str);
                }
            }
        }

        /**
         * Method for getting server status fields
         *
         * @return array
         * @since 2.0
         */
        public static function getServerStatusFields()
        {
            return [
                'Primary Domain' => get_option('ssl_zen_base_domain', ''),
                'IP Address' => $_SERVER['SERVER_ADDR'], // gethostbyname(get_option( 'ssl_zen_base_domain', '' ));
                // 'cPanel Version'  => self::getCPanelVersion(),
                'Server' => self::getServerVersion(),
                'PHP version' => phpversion(),
                'cURL support' => self::getCurlActiveStatus(),
                'allow_url_fopen' => self::getAllowUrlFOpenActiveStatus(),
                'shell_exec' => self::getShellExecStatus(),
                'SSL version' => self::getSSLversion(),
                'Home Directory' => get_home_path()
            ];
        }

        /**
         * Method for getting WordPress status fields
         *
         * @return array
         * @since 2.0
         */
        public static function getWordPressStatusFields()
        {
            global $wp_version;

            return [
                'WordPress address (URL)' => get_option('home', ''),
                'Site address (URL)' => get_option('siteurl', ''),
                'WordPress version' => $wp_version,
                'SSL installed' => self::getSslInstallationStatus(),
                'Plugin version' => SSL_ZEN_PLUGIN_VERSION
            ];
        }

        /**
         * Check weather the base domain contains www sub domain
         *
         * @param $baseDomain
         *
         * @return bool
         * @since 2.0.4
         *
         */
        public static function checkWWWSubDomainExistence($baseDomain)
        {
            return strpos($baseDomain, 'www.') === 0;
        }

        /**
         * Log message.
         *
         * @param $msg
         * @param $type
         * @param $write_to_db
         *
         */
        public static function log($msg, $type = 'debug', $write_to_db = true)
        {
            // always write to default error log if switch is on or type is error or warning.
            if (SSL_ZEN_PLUGIN_ALLOW_DEBUG || in_array($type, array('error', 'warn'), true)) {
                error_log(sprintf('%s --- %s: %s', 'SSLZenClient', strtoupper($type), $msg));
            }

            if (SSL_ZEN_PLUGIN_ALLOW_DEV) {
                return;
            }

            if ($write_to_db) {
                $format = date_i18n(get_option('date_format') . ' ' . get_option('time_format'));
                $log = get_transient('sslzen-debug');
                if (false === $log) {
                    $log = array();
                }
                $log[] = sprintf('%s (%s): %s', $format, $type, $msg);
                set_transient('sslzen-debug', $log);
            }
        }

        /**
         * Remove logs from db and file.
         *
         */
        public static function removeLogs()
        {
            $dirs = wp_get_upload_dir();
            $file = trailingslashit($dirs['basedir']) . 'ssl-zen/debug.log';

            delete_transient('sslzen-debug');
            wp_delete_file($file);
        }

        /**
         * Export logs from db as file.
         *
         */
        public static function exposeLogAsFile()
        {
            $dirs = wp_get_upload_dir();

            $dir = trailingslashit($dirs['basedir']) . 'ssl-zen';
            wp_mkdir_p($dir);
            $file = $dir . '/debug.log';

            $log = get_transient('sslzen-debug');
            if (false !== $log) {
                $contents = '';
                foreach ($log as $line) {
                    $contents .= $line . PHP_EOL;
                }
                $bytes = file_put_contents($file, $contents);
                if (empty($bytes)) {
                    self::log(sprintf('Unable to write to file %s', $file), 'error', false);
                    return false;
                }
            } else {
                file_put_contents($file, __('No logs in the database', 'ssl-zen'));
            }

            return trailingslashit($dirs['baseurl']) . 'ssl-zen/debug.log';
        }

        /**
         * Get the current site stackpath edge name
         * @param $records
         * @return string|null
         */
        public static function getStackpathEdgeName($records)
        {
            $stackpathCdnDomain = '.stackpathcdn.com';
            $edge = null;
            if (array_key_exists('www', $records)) {
                $wwwValue = $records['www']['value'];
                if ($wwwValue && stripos($wwwValue, $stackpathCdnDomain) !== false) {
                    // The www domain contains stackpathcdn edge, get it
                    $edge = str_replace($stackpathCdnDomain, '', $wwwValue);
                }
            }
            return $edge;
        }

        /**
         * Returns the domain of the website
         * @return mixed|string
         */
        public static function getDomain()
        {
            $urlInfo = parse_url(get_site_url());
            return (isset($urlInfo['host']) ? $urlInfo['host'] : '');
        }

    }
}
