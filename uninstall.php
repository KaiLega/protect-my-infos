<?php
/**
 * Uninstall Protect My Infos plugin
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit; // Exit if accessed directly
}

// Meta key to remove
$meta_key = 'yw_protect_my_infos_dismissed_notice';

// Retrieve all users
$users = get_users();

foreach ($users as $user) {
    // Safely delete the meta key for each user
    delete_user_meta($user->ID, $meta_key);
}
