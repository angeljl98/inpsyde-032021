<?php

use DomainConnect\Exception\DomainConnectException;
use DomainConnect\Exception\InvalidDomainConnectSettingsException;
use DomainConnect\Exception\InvalidDomainException;
use DomainConnect\Exception\NoDomainConnectRecordException;
use DomainConnect\Exception\NoDomainConnectSettingsException;
use DomainConnect\Services\DnsService;
use DomainConnect\Services\TemplateService;
use GuzzleHttp\Client;


if (!class_exists('ssl_zen_domainconnect')) {
    /**
     * Class to get a domainconnect URL to add DNS records
     */
    class ssl_zen_domainconnect
    {

        /**
         * Get the domain connect URL of the domain
         * @param null $edge
         * @return string|null
         */
        public static function getUrl($edge = null)
        {
            $applyUrl = null;
            $domain = ssl_zen_helper::getDomain();
            try {
                $applyUrl = (new TemplateService())->getTemplateSyncUrl(
                // Remove www, when changing the URL with DC, otherwise it uses www as @ (base domain)
                // @TODO: Test this with subdomains eg. abc.sslzen.xyz
                    str_replace('www.', '', $domain),
                    'sslzen.com',
                    'cdn',
                    [
                        'edge' => $edge ? $edge : $domain
                    ]
                );
            } catch (DomainConnectException $e) {
                ssl_zen_helper::log((sprintf('Failed to fetch domainconnect URL: %s', $e->getMessage())));
            }
            return $applyUrl;
        }

        /**
         * Checks whether the domain has domainconnect enabled
         * @return bool
         */
        public static function isEnabled()
        {
            // true or false would be hard to catch via get_option
            $status = 'disabled';
            $saved_status = get_option('ssl_zen_domainconnect_status');
            // if the saved status option exists use it
            if ($saved_status !== false) {
                // Check if saved status is "enabled"
                return $saved_status === 'enabled';
            }
            // Get the domain to check domain connect status
            $domain = ssl_zen_helper::getDomain();
            if ($domain) {
                $client = new Client([]);
                $dnsService = new DnsService($client);
                try {
                    $domainSettings = $dnsService->getDomainSettings($domain);
                    $status = 'enabled';
                } catch (InvalidDomainConnectSettingsException $idcse) {
                } catch (InvalidDomainException $ide) {
                } catch (NoDomainConnectSettingsException $ndcse) {
                } catch (NoDomainConnectRecordException $ncre) {
                }
                // if it throws any one of the exceptions, it doesn't support DC
            }
            // we don't care about the returned response,
            // save the domain connect status for quicker references
            update_option('ssl_zen_domainconnect_status', $status);
            return $status === 'enabled';
        }
    }
}
