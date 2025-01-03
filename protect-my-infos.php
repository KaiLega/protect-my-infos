<?php
/*
 Plugin Name: Protect My Infos
 Plugin URI: https://www.yugaweb.com/
 Description: Protect phone numbers and email addresses from bots using advanced encryption and various protection methods, ensuring visibility only to human users. Highly customizable, this plugin allows you to select which data to protect and how, offering flexibility and robust security for your website.
 Version: 1.3.0
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

// Load plugin text domain for localization
function protect_my_infos_load_textdomain() {
    load_plugin_textdomain('protect-my-infos', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'protect_my_infos_load_textdomain');

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';

function protect_my_infos_admin_notice() {
    echo '<div class="notice notice-info is-dismissible">
        <p>' . __('Thank you for using Protect My Infos! ', 'protect-my-infos') . '<a href="https://yugaweb.com" target="_blank">' . __('Visit our website for updates.', 'protect-my-infos') . '</a></p>
    </div>';
}
add_action('admin_notices', 'protect_my_infos_admin_notice');


?>
