=== SSL Zen - Free SSL Certificate & HTTPS Redirect for WordPress ===
Contributors: sslzen, sslzensupport, freemius
Donate link: https://paypal.me/sagarspatil
Tags: ssl,free ssl,ssl certificate,https,mixed content,insecure content,force ssl
Requires at least: 4.2
License: GPL3
Tested up to: 5.7
Requires PHP: 5.4
Stable tag: 3.1.2

Helps install a free SSL certificate, fixes mixed content, insecure content by redirecting to https, and forces SSL on all pages.

== Description ==

### Secure your website with a Free SSL certificate. Trusted by 100,000+ websites globally! ###

https://www.youtube.com/watch?v=Jk86wUqoOco

[SSL Zen](https://sslzen.com) helps you to secure your website, protect your customer's data and show your visitors you're trustworthy and authentic.


= Features of Free Version: =
* Secure padlock in the browser
* Displays 'connection is secure' message
* Increased SEO ranking
* Customers will trust you
* Accept safe payments online
* Compliance with regulation standards
* Strong bank-level security

Note: The free version requires you to manually verify your domain name with Let's Encrypt by uploading a file on your server. You will also need to upload the SSL certificates on your server and configure them.
SSL certificates from Let's Encrypt are only valid for [90 days](https://letsencrypt.org/2015/11/09/why-90-days.html) and need to be manually renewed. If you fail to renew, your website will start showing a not secure warning to the visitors.
If you want the plugin to automatically install the SSL certificate and auto-renew it, please check the premium version of the plugin.

= How to buy the Premium version of the plugin? =
Download the free version of the plugin by clicking on the Download button at the top. Install and Activate the plugin. On the Pricing Page, select the Premium version, complete the payment. You will receive a plugin download link along with your license key.

= Features of Premium Version: =
* Automatic Domain Verification
* Automatic SSL Certificate Generation
* Automatic SSL Certificate Renewal
* Accelerate your website with StackPath's Global Content Delivery Network
* Premium Support - Live Chat, Email Support
* Spam Protection
* Brute-Force Protection
* Forces Browser Validation on traffic anomalies
* Performs Real-time threat intelligence for IP addresses, source location, and information on malicious IPs.
* Patches known vulnerabilities in the Apache Struts framework by blocking requests suspected of exploiting these vulnerabilities
* Enables a set of rules designed to block common Wordpress exploits
* Blocks clients performing multiple injection attacks.
* Blocks Probing and Forced Browsing
* Blocks SQL injection attack attempts
* Blocks Cross-Site-Scripting (XSS) attack attempts
* Blocks Shellshock attack attempts
* Blocks requests suspected of being a Remote File Inclusion attempt
* Blocks requests suspected of a Local File Inclusion attempt
* Blocks attempts to access and potentially harm your servers through backdoors
* Blocks requests suspected of web shell attempts
* Blocks requests suspected of Response header injection attempts
* Blocks Invalid User Agents
* Blocks Unknown User Agents


= Why get a SSL padlock? =
* Trust - Starting from July 2018, Google Chrome has begun to mark all non SSL-websites as ‘Not Secure’. When your users see the broken padlock, their trust wavers!
* Security - SSL provides authentication, trust and compliance. If your customer is filling out forms, or making payments on your website, you need SSL to protect sensitive data from eavesdroppers.
* SEO - Google ranks SSL-enabled websites higher than unsecured websites. Hence, securing your website also helps get you on top of Google’s search results.

= Please leave a review =
If our plugin helped you secure your website, please leave a [review here](https://wordpress.org/support/plugin/ssl-zen/reviews/#new-post)

For more information about our plugin, please visit [sslzen.com](https://sslzen.com)

= Want SSL Zen plugin in your language? =
You can directly translate the plugin in your language [here](https://translate.wordpress.org/projects/wp-plugins/ssl-zen/)
When you add translations, get in touch with us as we will get you listed as a Project Translation Editor for our plugin.

= Legal: =
- By downloading our plugin, You agree to Let’s Encrypt® [Terms of Service](https://letsencrypt.org/documents/LE-SA-v1.2-November-15-2017.pdf)
- By downloading our plugin, You agree to [LEClient license terms](https://github.com/yourivw/LEClient/blob/master/LICENSE), a PHP LetsEncrypt client library to verify domain ownership and generate SSL certificate for your website.
- We use [Let's Debug](https://letsdebug.net/) API, a diagnostic tool to help figure out why you might not be able to issue a certificate for Let's Encrypt®.
- We use [Freemius](https://freemius.com) to collect non-sensitive diagnostic data about your website should you opt-in.

Internet Security Research Group™, Let’s Encrypt®, ISRG™ are trademarks of the Internet Security Research Group. StackPath®, EdgeSSL™ are trademarks of StackPath, LLC. All rights reserved.

== Installation ==
To install this plugin:

1. Make a backup of your website and database
2. Download the plugin
4. Upload the plugin to the wp-content/plugins directory,
5. Go to “plugins” in your WordPress admin, then click activate.
6. You will now see a SSL Zen icon on your left navigation bar. Click on it and follow the step by step guide.

== Frequently Asked Questions ==

= What is a SSL Certificate? =
An SSL (Secure Sockets Layer) certificate is a digital certificate that authenticates the identity of a website and encrypts information sent to the server using SSL technology.

= Are your SSL certificates really free? =
Yes. All SSL certificates are generated by Let’s Encrypt® (nonprofit) with a mission to create a more secure and privacy-respecting Web by promoting the widespread adoption of HTTPS.

= What benefits does SSL provide? =
An SSL Cert protects your customers’ sensitive information such as their name, address, password, or credit card number by encrypting the data during transmission from their computer to your web server.

= How do I install my SSL certificate? =
You will be emailed a certificate and a private key. Simply go to your cPanel dashboard, click on SSL/TLS and follow the process to install SSL certificate.

= How long are the certificates valid? =
Let's Encrypt® certificates are valid for 90 days and can be renewed with no additional cost. You can easily renew them through our plugin.

= How do I renew an SSL certificate? =
Simply follow the same process you used to generate and install SSL certificate the first time and your certificates will be renewed.

= Do I need technical knowledge to set up an SSL? =
Our free version of the plugin does require you to upload files on your website to verify domain ownership.

= Is your plugin safe to install on my website? =
Our plugin is open-source and anyone can download and inspect it before installing on their website.

= Do you support Wildcard SSL? =
We currently do not support wildcard SSL. Our plugin is only built to generate SSL certificate for your WordPress website/blog.

= Which browsers will trust my certificate? =
All major browsers are supported.

= What is the verification process? =
The verification is a simple process to prove that you own the domain for which you are requesting the certificate.

= Do I need to do the verification again when I renew? =
The verification results are valid, at the time of writing, for 30 days. If you renew within that period, you should not need to re-verify. Outside of that period you will need to do the verification again. However, it is a very simple process that requires almost no time.

= I have installed the certificate correctly, but my site is not shown as "Secure" =
The most likely reason for this is that you are still loading some resources, such as images for example, via HTTP. Browsers in this case will show "Mixed content" errors in the developers console. If you do not want to use developer tools, you can easily check which resources those might be by using ["Why No Padlock?"](https://whynopadlock.com) service. You can read more about "Mixed Content" and how to prevent it [here](https://developers.google.com/web/fundamentals/security/prevent-mixed-content/fixing-mixed-content).

= Can I see the name of my company on the certificate? =
Free certificates are of Domain Validation type (DV) and do not hold that information. To have the information about your company, the certificate would need to pass Organization Validation (OV) or Extended Validation (EV). Both require paperwork (such as actual company checks) and cannot be offered for free as a result.

== Screenshots ==
1. How it works
2. Agree to Let's Encrypt ® TOS
3. Verify domain ownership by uploading files on your server
4. Install SSL certificate on your web server
5. Redirect http to https
6. Renew SSL certificate
7. Settings to enable 301 redirection the settings page.

== Changelog ==

= 3.1.2 =
* Switch exec to shell_exec for all cases

= 3.1.1 =
* Set timeout for request to detect cpanel
* Fix issues with Reset Plugin flow for Stackpath CDN
* Fix persistent review notification!

= 3.1.0 =
* Fix file verification flow for WP sites in directories
* Add detection for cloudflare
* Add detection for bluehost temporary domains
* Add link to translate plugin
* Feedback prompt update
* Minor bug fixes

= 3.0.19 =
* Fix localhost check

= 3.0.18 =
* Fix Step2 premium CDN flow for Google domains
* Fix Step1 flow for checking IP addresses
* Fix check for cPanel's existence

= 3.0.17 =
* Fixed issues with free plugin site crashes
* Add translators to translations

= 3.0.16 =
* Fixed a bug that wasn't displaying cPanel form on Step 1

= 3.0.15 =
* Bug fix for Generic Free version

= 3.0.14 =
* Automated Step 2 for cPanel Free Users
* Display Remind Me Later link after 10 seconds

= 3.0.13 =
* Removed confusing message on Settings page asking users to change their A and CNAME record.

= 3.0.12 =
* StackPath bug fixes

= 3.0.11 =
* StackPath enhancements and bug fixes

= 3.0.10 =
* Updated Freemius SDK to 2.4.1
* Fixed a bug that wasn't displaying success message after DNS verification
* Changed pricing for premium plans from monthly to yearly

= 3.0.9 =
* Bug fix for DNS verification page design layout

= 3.0.8 =
* Changed pricing structure for premium versions

= 3.0.7 =
* Improved Re-Activation process for StackPath
* Improved error messaging

= 3.0.6 =
* Changed pricing of premium version to $36

= 3.0.5 =
* Updated Freemius SDK
* Improved StackPath DNS verification
* Added option to reset plugin for StackPath

= 3.0.4 =
* Pricing page has been hidden so users are redirected to buy the correct plan
* Updated cPanel Availability script

= 3.0.3 =
* Added better error messages for StackPath DNS verification

= 3.0.2 =
* Fixed a bug that was not displaying Let's Encrypt Debug Log

= 3.0.1 =
* Fixed a bug that would pass A record for www.domain.tld instead of domain.tld for StackPath
* Added a Debug button for the users to easily share the file with support team
* Added HelpScout beacon to StackPath Premium version.
* Added I agree to terms and conditions that was accidentally removed in 3.0.0

= 3.0.0 =
* Added StackPath Integration. Non-cPanel users can now use StackPath to get an SSL certificate.

= 2.2.1 =
* Added hyperlink for certificate name on Step 3. Added tooltip for copy and download button.
* Increased delay for Let's Debug calls as the previous delay was still not sufficient for few websites.

= 2.2 =
* Added a delay for Let's Debug calls
* Moved Enable Debugging from Advanced > Debug tab
* Added instructions for non cPanel SSL installation
* Removed fopen as a requirement in System Requirements Check
* Fixed a bug that wasn't renewing SSL certs for Pro customers

= 2.1.2 =
* Bug fix for DNS verification
* We now show settings page from Step 1
* Live Chat Widget for Premium users

= 2.1.1 =
Bug fix for 'include www' checkbox which was checking for A record only, replaced it with A or CNAME record.

= 2.1 =
* Changed pricing to per year from lifetime on pricing page
* Disabled upgrade to Pro in non-cPanel version
* Creates SSL cert for only domain.tld or www.domain.tld based on website url
* Added functionality so that the pro version of the plugin works even if the website is hosted in a sub-directory
* Added debug messages for identifying issues with Let's Encrypt

= 2.0.3 =
Bug fix for cPanel credential verification.

= 2.0.2 =
Updated Let's Encrypt PHP library LE Client to 1.2.2. Please update immediately.

= 2.0.1 =
Bug fixes.

= 2.0 =
* Major Plugin Redesign
* Added DNS verification which now supports wildcard SSL certificate
* Added System Requirements Compatibility Test
* Added option to send debug information for easier troubleshooting
* Removes HTML verification files after LE verification is complete
* Optimized HTTP and DNS verification process
* Updated LEClient to 1.2.1
* Added a pricing page which lets users upgrade to pro version
* Removed Review Plugin notice and donation requests from Admin Dashboard
* Added warning if plugin is installed on a localhost or IP address
* If 'include www' is checked, we now verify that a 'A' record for it exists
* Improved pro license validation
* Improved SSL installation process
* Optimized source code

= 1.13 =
* Replaced support email with plugin contact page
* Add a Setup menu item in plugin navigation
* Replaced the error message in Step 4
* Updated LE Client to the latest version

= 1.12 =
* Optimized codebase
* Bug Fix - Freemius auto-update process

= 1.11.2 =

* Bug fix

= 1.11.1 =

* Bug fix

= 1.11 =

* Added support to add SSL certificate for non-cPanel websites
* Fixed a bug that was not renewing an SSL certificate if the current url was https by ignoring https redirection for .well-known folder
* Fixed 'Terms and Conditions' link which was not working as expected
* If someone closes the review notice, we do not show it again for 24 hours
* Customized Freemius welcome message
* Updated Freemius SDK to 3.3.2
* If a user's SSL certificate has expired and he clicks on Renew in the Settings tab, we now take him to Step 1 to re-create his authorization
* Users can now download the SSL certificate on Step 3
* Fix a bug that was not detecting cPanel compatibility because sslverify was true
* Added new videos for cPanel and Apache SSL installation instructions with voice-over

= 1.10 =

* Like the plugin? Become our ambassador and earn cash
* Refer new customers to our plugin and earn 20% commission on each successful sale you refer!
* To become an affiliate, go to SSL Zen plugin and click on affiliation in the left menu.
* Added new tags ssl,ssl certificate,openssl,letsencrypt,free ssl,free ssl certificate,ssl tls,secure socket layer,ssl encryption,wildcard ssl certificate,letsencrypt wildcard,ssl https,tls,wordpress ssl

= 1.9.6 =

* Security Patch for 1.9.5
* Changed tags to https, ssl, free ssl, ssl certificate, mixed content,insecure content,force ssl

= 1.9.5 =

* Fixed security issue
