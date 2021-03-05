<?php

if (!class_exists('ssl_zen_messages')) {

    /**
     * Class to get messages
     *
     * @since 2.0
     */
    class ssl_zen_messages
    {

        /**
         * Get messages
         */
        public static function getMessages()
        {
            $baseDomain = get_option('ssl_zen_base_domain', __('your domain', 'ssl-zen'));

            return array(
                'dlerr' => [
                    'msg' => __('Error downloading verification file.', 'ssl-zen') . self::getContactLink(),
                    'type' => 'error',
                ],
                'dlerr_general' => [
                    'msg' => __('Error downloading file.', 'ssl-zen') . self::getContactLink(),
                    'type' => 'error',
                ],
                'successfully_generated' => [
                    'msg' => __('You have successfully generated a free SSL Certificate for your website.', 'ssl-zen'),
                    'type' => 'success',
                ],
                'invalid' => [
                    'msg' => __('Verifying domain ownership failed. Please check that the files listed below are uploaded to "web-root/.well-known/acme-challenge/" directory (web-root can be public_html or www or htdocs). Upgrade to our Pro version to automatically verify domain ownership.', 'ssl-zen'),
                ],
                'directory_permission' => [
                    'msg' => __('Could not create directory to verify domain ownership.', 'ssl-zen') . self::getContactLink(),
                ],
                'directory_permission2' => [
                    'msg' => __('Sorry, token for domain was NOT SAVED at .well-known/acme-challenge due to some issue. Please make a directory \'.well-known\' (with permission 0755) in root directory and try again.', 'ssl-zen'),
                ],
                'token_missmatch' => [
                    'msg' => __('Token does not match the content of ' . base64_decode($_REQUEST['uri']) . (isset($_REQUEST['host']) ? '. Either ' . base64_decode($_REQUEST['host']) . ' is not pointed
					to this server or unavailable over HTTP due to some server-side issue. Please fix this issue and try again later.' : ''), 'ssl-zen'),
                ],
                'not_all_authorizations_are_valid' => [
                    'msg' => __('Not all authorizations are valid.', 'ssl-zen') . self::getContactLink(),
                ],
                'api_error' => [
                    'msg' => __('LetsEncrypt API error.', 'ssl-zen') . self::getContactLink(),
                    'type' => 'error',
                ],
                'cpanel_not_exist' => [
                    'msg' => __('Your hosting provider does not use cPanel hosting software. Our plugin only works with cPanel based hosting. Please raise a support ticket by sending an email to support@sslzen.com', 'ssl-zen'),
                    'type' => 'warning',
                ],
                'cpanel_cant_connect' => [
                    'msg' => __('Oops! We can\'t connect to your cPanel. Please recheck the cPanel settings and provide correct credentials. If the problem still persists, please raise a support ticket by sending an email to support@sslzen.com', 'ssl-zen'),
                    'type' => 'warning',
                ],
                'error' => [
                    'msg' => __('Failed to detect a valid SSL certificate. Your website can take a few minutes to recognize the SSL certificate.', 'ssl-zen') . ' ' . __('Please try again after some time.', 'ssl-zen') . ' ' . __('If it still fails, you have not installed the SSL certificate properly.', 'ssl-zen') . self::getContactLink(),
                    'type' => 'error',
                ],
                'error_params' => [
                    'msg' => __('There are some technical issue, please send an email to support@sslzen.com', 'ssl-zen'),
                ],
                'error_sleep_15' => [
                    'msg' => __('Please wait 15 minutes, then try again.', 'ssl-zen'),
                ],
                'error_sleep_5' => [
                    'msg' => __('Please wait 5 minutes, then try again.', 'ssl-zen'),
                ],
                'nonce_error' => [
                    'msg' => __('Nonce error.', 'ssl-zen'),
                ],
                'host_error' => [
                    'msg' => __('There is no host specified initially.', 'ssl-zen'),
                ],
                'error_rate_limit' => [
                    'msg' => __('Calls rate error, please try a little bit later.', 'ssl-zen'),
                ],
                'error_wp' => [
                    'msg' => __('Connection issue.', 'ssl-zen'),
                ],
                'cpanel_install_ssl_err1' => [
                    'msg' => __('The install_ssl cURL call did not return valid JSON: Sorry, there was a problem installing the SSL on domain.', 'ssl-zen') . self::getContactLink(),
                ],
                'cpanel_install_ssl_err2' => [
                    'msg' => self::getContactLink() . __('The install_ssl cURL call returned valid JSON, but reported errors: ' . base64_decode($_REQUEST['msg']), 'ssl-zen')
                ],
                'wrong_cred' => [
                    'msg' => __('Oops! We can\'t connect to your cPanel. Please recheck the cPanel settings and provide correct credentials. If the problem still persists,', 'ssl-zen') . self::getContactLink(false),
                ],
                'success_settings' => [
                    'msg' => __('Settings saved successfully.', 'ssl-zen'),
                    'type' => 'success'
                ],
                'lock' => [
                    'msg' => __('.htaccess file has been locked. Remember to disable this option once you have made changes to .htaccess file.', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'cron_error' => [
                    'msg' => self::getCronErrorMessage(),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_invaliddomain' => [
                    'msg' => __('"' . $baseDomain . '" is not a valid domain name that Let\'s Encrypt would be able to issue a certificate for.', 'ssl-zen'),
                    'type' => 'error'
                ],
                'lets_encrypt_error_statusnotoperational' => [
                    'msg' => __('Let’s Encrypt is currently experiencing an outage, please try again in some time. For real-time status, please visit - https://letsencrypt.status.io/', 'ssl-zen'),
                    'type' => 'error'
                ],
                'lets_encrypt_error_ratelimit' => [
                    'msg' => __('"' . $baseDomain . '" is currently affected by Let\'s Encrypt-based rate limits (https://letsencrypt.org/docs/rate-limits/). You may review certificates that have already been issued by visiting https://crt.sh/?q=%your_domain . Please note that it is not possible to ask for a rate limit to be manually cleared.
The Duplicate Certificate limit (5 certificates with the exact same set of domains per week) has been exceeded and is affecting the domain "' . $baseDomain . '". The exact set of domains affected is: "' . $baseDomain . '". It may be possible to avoid this rate limit by issuing a certificate with an additional or different domain name.', 'ssl-zen'),
                    'type' => 'error'
                ],
                'lets_encrypt_error_issuefromletsencrypt' => [
                    'msg' => __('A test authorization for your domain to the Let\'s Encrypt staging service has revealed issues that may prevent any certificate for this domain being issued.
Error creating new order :: Policy forbids issuing for name', 'ssl-zen'),
                    'type' => 'error'
                ],
                'lets_encrypt_error_badredirect' => [
                    'msg' => __('Sending an ACME HTTP validation request to your domain results in an unacceptable redirect. This is most likely a misconfiguration of your web server or your web application. Check your web server configuration and .htaccess for Redirect/RedirectMatch/RewriteRule.', 'ssl-zen'),
                    'type' => 'error'
                ],
                'lets_encrypt_error_anotworking' => [
                    'msg' => __('"' . $baseDomain . '" has an A (IPv4) record (1.2.3.4) but a request to this address over port 80 did not succeed.
A timeout was experienced while communicating with ' . $baseDomain . '/1.2.3.4: Get http://' . $baseDomain . '/.well-known/acme-challenge/letsdebug-test: dial tcp 1.2.3.4:80: i/o timeout', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_txtdoublelabel' => [
                    'msg' => __('Some DNS records were found that indicate TXT records having been incorrectly manually entered into DNS editor interfaces. The correct way to enter these records is to either remove the domain from the label (so "_acme-challenge.example.org" is entered just as "_acme-challenge.www") or include a period (.) at the end of the label (so "_acme-challenge.example.org.").', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_blockedbynginxtestcookie' => [
                    'msg' => __('The validation request to this domain was blocked by a deployment of the nginx testcookie module (https://github.com/kyprizel/testcookie-nginx-module). This module is designed to block robots, and causes the Let\'s Encrypt validation process to fail. The server administrator can solve this issue by disabling the module (`testcookie off;`) for requests under the path of `/.well-known/acme-challenge/`.', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_httponhttpsport' => [
                    'msg' => __('A validation request to this domain resulted in an HTTP request being made to a port that expects to receive HTTPS requests. This could be the result of an incorrect redirect (such as to http://example.com:443/) or it could be the result of a webserver misconfiguration, such as trying to enable SSL on a port 80 virtualhost.', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_cloudflarecdn' => [
                    'msg' => __('The domain "' . $baseDomain . '" is being served through Cloudflare CDN. Any Let\'s Encrypt certificate installed on the origin server will only encrypt traffic between the server and Cloudflare. It is strongly recommended that the SSL option \'Full SSL (strict)\' be enabled.', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_cloudflaresslnotprovisioned' => [
                    'msg' => __('"' . $baseDomain . '" is being served through Cloudflare CDN and a certificate has not yet been provisioned yet by Cloudflare.', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_multipleipaddressdiscrepancy' => [
                    'msg' => __('"' . $baseDomain . '" has multiple IP addresses in its DNS records. While they appear to be accessible on the network, we have detected that they produce differing results when sent an ACME HTTP validation request. This may indicate that some of the IP addresses may unintentionally point to different servers, which would cause validation to fail.', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'lets_encrypt_error_aaaanotworking' => [
                    'msg' => __('"' . $baseDomain . '" has an A (IPv4) record but a request to this address over port 80 did not succeed.', 'ssl-zen'),
                    'type' => 'warning'
                ],
                'invalid_ip_address' => [
                    'msg' => __('Due to some network restrictions we\'re unable to validate your IP address. Please send an email to support@sslzen.com', 'ssl-zen'),
                    'type' => 'error'
                ]
            );
        }

        /**
         * Get message from the list by key
         *
         * @param $key
         *
         * @return mixed
         */
        public static function getMessage($key)
        {
            return @self::getMessages()[$key];
        }

        public static function getHtaccessMessage()
        {
//		case 'writeerr':
//						$msg = __( '.htaccess file not writable. Settings saved successfully.', 'ssl-zen' );
//						if ( get_option( 'ssl_zen_enable_301_htaccess_redirect', '' ) == '1' ) {
//							$msg .= '<br>' . __( 'Manually paste the following lines of code to your .htaccess files or make the file writable.',
//									'ssl-zen' );
//
//							$rules       = self::get_htaccess_rules();
//							$arr_search  = array( "<", ">", "\n" );
//							$arr_replace = array( "&lt", "&gt", "<br>" );
//							$rules       = str_replace( $arr_search, $arr_replace, $rules );
//
//							$msg .= '<code>' . $rules . '</code>';
//						}
//						break;
//
//					case 'lock':
//						$msg = __( '.htaccess file is lock, could not write .htaccess file. Settings saved successfully.',
//							'ssl-zen' );
//						break;
//
//					case 'renewlater':
//						$msg = sprintf( __( 'You can renew your certificate after %s date.', 'ssl-zen' ),
//							get_option( 'ssl_zen_certificate_60_days', '' ) );
//						break;
//
//					case 'success':
//						$msg   = __( 'Settings saved successfully.', 'ssl-zen' );
//						$class = 'updated';
//						break;
        }

        /**
         * Get contact page
         *
         * @param bool $upperCase
         *
         * @return string
         */
        public static function getContactLink($upperCase = true)
        {
            $message = 'Click here to report an issue.';
            $message = $upperCase ? $message : strtolower($message);

            return sprintf(' <a href="' . admin_url('admin.php?page=ssl_zen-contact') . '">%s</a>', __($message, 'ssl-zen'));
        }

        /**
         * Get error message for cron
         */
        public static function getCronErrorMessage()
        {
            return __('We were unable to add a cron job automatically. LetsEncrypt SSL certificates are only valid for 90 days and you are required to renew the certificate.', 'ssl-zen') . __('To automatically renew the certificates, please add a cron job using cPanel.', 'ssl-zen') . __('Follow this video guide - https://youtu.be/YwpUjz1tMbA?t=77', 'ssl-zen') . '<br>' . __('Please add the below command to the cron job and select Once Per Day in Common Settings:', 'ssl-zen') . '<br>' . '<strong>' . __('php -q ' . str_replace("classes", "", __DIR__) . 'cron.php >/dev/null 2>&1', 'ssl-zen') . '</strong>';
        }
    }
}
