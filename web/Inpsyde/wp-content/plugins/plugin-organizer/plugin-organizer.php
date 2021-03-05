<?php
/*
Plugin Name: Plugin Organizer
Plugin URI: https://www.sterupdesign.com/dev/wordpress/plugins/plugin-organizer/
Description: A plugin to disable plugins on indivudual pages and change the order that they are loaded in.
Version: 10.1.4
Author: Jeff Sterup
Author URI: https://www.sterupdesign.com
License: GPL2
*/

require_once(WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) . "/lib/PluginOrganizer.class.php");

$PluginOrganizer = new PluginOrganizer(__FILE__);
?>