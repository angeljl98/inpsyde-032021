<?php
/**
 * abc-demo-plugin
 *
 * Plugin Name: angel-badwords-plugin
 * Plugin URI:  https://github.com/angeljl98/
 * Description: Filter bad words in a post
 * Version:     1.0
 * Author:      Angel Lucena
 * Author URI:  https://torre.co/en/angeljl98
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: classic-editor
 * Domain Path: /
 * Requires at least: 5.4
 * Requires PHP: 7.2.9
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

add_filter ( 'comment_text', 'filter_profanity', 10, 1 );

function filter_profanity( $content ) {
	$profanities = array('groseria');
	$content = str_ireplace( $profanities, '{censored}', $content );
	return $content;
}