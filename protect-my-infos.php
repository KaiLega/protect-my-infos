<?php
/*
 Plugin Name: Protect My Infos
 Plugin URI: https://www.yugaweb.com/protect-my-infos/
 Description: Discourage basic automated harvesting of phone numbers and email addresses using configurable client-side obfuscation and reveal controls.
 Version: 1.4.0
 Requires at least: 5.0
 Requires PHP: 7.4
 Author: Yuga Web
 Author URI: https://www.yugaweb.com/
 License: GPLv2 or later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: protect-my-infos
 Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!defined('YW_PLUGIN_FILE')) {
    define('YW_PLUGIN_FILE', __FILE__);
}

if (!defined('YW_PROTECT_MY_INFOS_VERSION')) {
    define('YW_PROTECT_MY_INFOS_VERSION', '1.4.0');
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/helpers.php';
