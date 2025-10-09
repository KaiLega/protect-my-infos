<?php

/**
 * Copyright (c) 2024–present Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */
 
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_yw_protect_my_infos_save_settings', function () {
    // Check nonce and permissions
    $nonce = isset($_POST['security']) ? sanitize_text_field(wp_unslash($_POST['security'])) : '';
    if (!wp_verify_nonce($nonce, 'yw_protect_my_infos_nonce_action')) {
        wp_send_json_error(__('Invalid nonce.', 'protect-my-infos'));
    }
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Permission denied.', 'protect-my-infos'));
    }

    // Parse the serialized form (entire form)
    $raw = isset($_POST['options']) ? wp_unslash($_POST['options']) : '';
    parse_str($raw, $parsed);
    $data = isset($parsed['yw_protect_my_infos_options']) ? $parsed['yw_protect_my_infos_options'] : [];
    if (!is_array($data)) {
        wp_send_json_error(__('Invalid options structure.', 'protect-my-infos'));
    }

    $sanitized = yw_protect_my_infos_sanitize_options($data);

    // Save the sanitized options
    update_option('yw_protect_my_infos_options', $sanitized);

    wp_send_json_success(__('Settings saved successfully.', 'protect-my-infos'));
});
