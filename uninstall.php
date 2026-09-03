<?php
/**
 * Uninstall Protect My Infos plugin
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit; // Exit if accessed directly
}

// Remove plugin settings.
delete_option('yw_protect_my_infos_options');

// Remove the dismissed-notice flag for every user without loading all users.
delete_metadata('user', 0, 'yw_protect_my_infos_dismissed_notice', '', true);
