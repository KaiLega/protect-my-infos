<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Handles AJAX save settings request
function yw_protect_my_infos_ajax_save_settings() {

    // Verify the nonce to prevent CSRF attacks
    $nonce = isset($_POST['security']) 
        ? sanitize_text_field(wp_unslash($_POST['security'])) 
        : (isset($_POST['yw_protect_my_infos_nonce_field']) 
            ? sanitize_text_field(wp_unslash($_POST['yw_protect_my_infos_nonce_field'])) 
            : '');

    if (!wp_verify_nonce($nonce, 'yw_protect_my_infos_nonce_action')) {
        wp_send_json_error(esc_html__('Invalid nonce.', 'protect-my-infos'));
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(esc_html__('Permission denied.', 'protect-my-infos'));
        return;
    }

    $raw_post_options = filter_input(INPUT_POST, 'options', FILTER_SANITIZE_STRING);

    if (!$raw_post_options || !is_string($raw_post_options)) {
        wp_send_json_error(esc_html__('Invalid options structure.', 'protect-my-infos'));
        return;
    }

    $post_options_unescaped = wp_unslash($raw_post_options);
    parse_str($post_options_unescaped, $parsed_options);

    if (!is_array($parsed_options) || !isset($parsed_options['yw_protect_my_infos_options'])) {
        wp_send_json_error(esc_html__('Invalid options structure.', 'protect-my-infos'));
        return;
    }

    $sanitized_options = array();
    foreach ($parsed_options['yw_protect_my_infos_options'] as $key => $value) {
        switch ($key) {
            case 'protect_phone_numbers':
            case 'protect_emails':
            case 'enable_obfuscation':
                $sanitized_options[$key] = intval($value);
                break;
            case 'show_icons':
                $sanitized_options[$key] = isset($value) ? intval($value) : 0;
                break;
            case 'text_color':
            case 'icons_color':
                $sanitized_options[$key] = sanitize_hex_color($value);
                break;

            case 'yw-obfuscation_type':
            case 'blur_mode':
                $sanitized_options[$key] = sanitize_text_field($value);
                break;

            case 'reveal_phone_text':
            case 'reveal_email_text':
                $sanitized_options[$key] = sanitize_text_field($value);
                break;

            default:
                break;
        }
    }

    $existing_options = get_option('yw_protect_my_infos_options');
    if ($sanitized_options !== $existing_options) {
        $update_result = update_option('yw_protect_my_infos_options', $sanitized_options);
        if (!$update_result) {
            wp_send_json_error(esc_html__('Failed to save settings.', 'protect-my-infos'));
            return;
        }
    }

    wp_send_json_success(esc_html__('Settings saved successfully.', 'protect-my-infos'));
}
add_action('wp_ajax_yw_protect_my_infos_save_settings', 'yw_protect_my_infos_ajax_save_settings');
