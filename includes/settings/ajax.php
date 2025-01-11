<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Handles AJAX save settings request
function protect_my_infos_ajax_save_settings() {
    $nonce = isset($_POST['security']) 
        ? sanitize_text_field(wp_unslash($_POST['security'])) 
        : (isset($_POST['protect_my_infos_nonce_field']) 
            ? sanitize_text_field(wp_unslash($_POST['protect_my_infos_nonce_field'])) 
            : '');

    if (!wp_verify_nonce($nonce, 'protect_my_infos_nonce_action')) {
        wp_send_json_error(esc_html__('Invalid nonce.', 'protect-my-infos'));
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(esc_html__('Permission denied.', 'protect-my-infos'));
        return;
    }

    $raw_post_options = filter_input(INPUT_POST, 'options', FILTER_DEFAULT);
    if (!$raw_post_options || !is_string($raw_post_options)) {
        wp_send_json_error(esc_html__('Invalid options structure.', 'protect-my-infos'));
        return;
    }

    $post_options_unescaped = wp_unslash($raw_post_options);
    parse_str($post_options_unescaped, $parsed_options);

    if (!is_array($parsed_options) || !isset($parsed_options['protect_my_infos_options'])) {
        wp_send_json_error(esc_html__('Invalid options structure.', 'protect-my-infos'));
        return;
    }

    $sanitized_options = array_map('sanitize_text_field', $parsed_options['protect_my_infos_options']);
    update_option('protect_my_infos_options', $sanitized_options);

    wp_send_json_success(esc_html__('Settings saved successfully.', 'protect-my-infos'));
}
add_action('wp_ajax_protect_my_infos_save_settings', 'protect_my_infos_ajax_save_settings');
