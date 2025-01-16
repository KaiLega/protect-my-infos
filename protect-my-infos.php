<?php
/*
 Plugin Name: Protect My Infos
 Plugin URI: https://www.yugaweb.com/protect-my-infos/
 Description: Protect phone numbers and email addresses from bots using advanced encryption and various protection methods, ensuring visibility only to human users. Highly customizable, this plugin allows you to select which data to protect and how, offering flexibility and robust security for your website.
 Version: 1.3.4
 Requires at least: 5.0
 Requires PHP: 7.2
 Author: Yuga Web
 Author URI: https://www.yugaweb.com/
 License: GPLv2 or later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: yw-protect-my-infos
 Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load plugin text domain for localization (for WordPress versions < 4.6)
function yw_protect_my_infos_load_textdomain() {
    if (version_compare(get_bloginfo('version'), '4.6', '<')) {
        load_plugin_textdomain('yw-protect-my-infos', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}
add_action('init', 'yw_protect_my_infos_load_textdomain');

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/helpers.php';
