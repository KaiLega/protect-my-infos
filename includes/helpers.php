<?php
/**
 * Helper functions for Protect My Infos plugin.
 * Copyright (c) 2024–present Yuga Web
 */

// Ensure this file is only accessed through WordPress
if (!defined('ABSPATH')) {
    exit;
}

// Load get_plugin_version() function
if (!function_exists('yw_protect_my_infos_get_plugin_version')) {
    require_once __DIR__ . '/enqueue.php';
}

/**
 * Display an admin notice 
 */
function yw_protect_my_infos_admin_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'toplevel_page_yw-protect-my-infos') {
        return;
    }

    // Check if the user has dismissed the notice
    $user_id = get_current_user_id();
    if (get_user_meta($user_id, 'yw_protect_my_infos_dismissed_notice', true)) {
        return; // Exit if the notice has been dismissed
    }

    // Prepare the notice content
    $notice_text = __('Thank you for installing Protect My Infos!', 'protect-my-infos');
    $visit_link_text = __('Visit our website for updates.', 'protect-my-infos');
    $dismiss_button_text = __('Do not show again', 'protect-my-infos');
    $dismiss_button_label = __('Do not show this notice again', 'protect-my-infos');
    $website_url = 'https://yugaweb.com/protect-my-infos/';

    // Generate the notice
    echo '<div class="yw-protect-my-infos-notice is-dismissible notice notice-info" style="display: flex; justify-content: space-between; align-items: center;">';
    echo '<p style="margin: 0;">' . esc_html($notice_text) . ' <a href="' . esc_url($website_url) . '" target="_blank" rel="noopener noreferrer">' . esc_html($visit_link_text) . '</a></p>';
    echo '<button type="button" class="yw-dismiss-notice button-link" aria-label="' . esc_attr($dismiss_button_label) . '" style="margin-left: auto;">' . esc_html($dismiss_button_text) . '</button>';
    echo '</div>';
}
add_action('admin_notices', 'yw_protect_my_infos_admin_notice');



/**
 * Handle the AJAX request to dismiss the admin notice.
 */
function yw_protect_my_infos_dismiss_notice() {
    // Check if it's an AJAX request
    if (!defined('DOING_AJAX') || !DOING_AJAX) {
        wp_die(
            esc_html__('Unauthorized request.', 'protect-my-infos'),
            esc_html__('Unauthorized request.', 'protect-my-infos'),
            array('response' => 403)
        );
    }

    // Check the nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'yw_protect_my_infos_dismiss_notice')) {
        wp_send_json_error(esc_html__('Invalid nonce.', 'protect-my-infos'));
        return;
    }

    // Check if the user has the required capability
    if (!current_user_can('manage_options')) {
        wp_send_json_error(esc_html__('Permission denied.', 'protect-my-infos'));
        return;
    }

    // Update the user meta to dismiss the notice
    $user_id = get_current_user_id();
    $updated = update_user_meta($user_id, 'yw_protect_my_infos_dismissed_notice', true);

    if (!$updated) {
        wp_send_json_error(esc_html__('Failed to update user meta.', 'protect-my-infos'));
        return;
    }

    wp_send_json_success(esc_html__('Notice dismissed.', 'protect-my-infos'));
}

add_action('wp_ajax_yw_dismiss_notice', 'yw_protect_my_infos_dismiss_notice');


/**
 * Enqueue the JavaScript for the admin notice.
 */
function yw_protect_my_infos_enqueue_admin_notice_scripts($hook) {
    if ($hook !== 'toplevel_page_yw-protect-my-infos' || !current_user_can('manage_options')) {
        return;
    }

    $user_id = get_current_user_id();
    if (get_user_meta($user_id, 'yw_protect_my_infos_dismissed_notice', true)) {
        return;
    }

    $plugin_version = yw_protect_my_infos_get_plugin_version();

    wp_enqueue_script(
        'yw-protect-my-infos-notice',
        plugins_url('assets/js/protect-my-infos-notice.js', YW_PLUGIN_FILE),
        array('jquery'),
        $plugin_version,
        true
    );

    wp_localize_script('yw-protect-my-infos-notice', 'ywProtectMyInfosNotice', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('yw_protect_my_infos_dismiss_notice'),
    ));
}
add_action('admin_enqueue_scripts', 'yw_protect_my_infos_enqueue_admin_notice_scripts');

// Get image URL by key
function yw_protect_my_infos_get_image_url($image_key) {
    $map = [
        'banner'  => 'admin-side.svg',
        'logo'    => 'protect_my_infos-logo.svg',
        'qr-code' => 'qr-code.svg',
    ];

    if (!isset($map[$image_key])) {
        return '';
    }

    // Determine the base file for plugins_url
    $base_file = defined('YW_PLUGIN_FILE') ? YW_PLUGIN_FILE : dirname(__DIR__) . '/protect-my-infos.php';

    $url = plugins_url('assets/img/' . $map[$image_key], $base_file);

    if (function_exists('yw_protect_my_infos_get_plugin_version')) {
        $url = add_query_arg('ver', yw_protect_my_infos_get_plugin_version(), $url);
    }

    return $url;
}
