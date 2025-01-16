<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

/**
 * Sanitization callback for plugin options.
 */
function yw_protect_my_infos_sanitize_options($input) {
    // Define an empty array for sanitized values
    $sanitized = array();

    // Sanitize each option individually
    if (isset($input['protect_phone_numbers'])) {
        $sanitized['protect_phone_numbers'] = intval($input['protect_phone_numbers']);
    }

    if (isset($input['protect_emails'])) {
        $sanitized['protect_emails'] = intval($input['protect_emails']);
    }

    if (isset($input['show_icons'])) {
        $sanitized['show_icons'] = intval($input['show_icons']);
    }

    if (isset($input['text_color'])) {
        $sanitized['text_color'] = sanitize_hex_color($input['text_color']);
    }

    if (isset($input['icons_color'])) {
        $sanitized['icons_color'] = sanitize_hex_color($input['icons_color']);
    }

    if (isset($input['enable_obfuscation'])) {
        $sanitized['enable_obfuscation'] = intval($input['enable_obfuscation']);
    }

    if (isset($input['yw-obfuscation_type'])) {
        $sanitized['yw-obfuscation_type'] = sanitize_text_field($input['yw-obfuscation_type']);
    }

    if (isset($input['blur_mode'])) {
        $sanitized['blur_mode'] = sanitize_text_field($input['blur_mode']);
    }

    if (isset($input['reveal_phone_text'])) {
        $sanitized['reveal_phone_text'] = sanitize_text_field($input['reveal_phone_text']);
    }
    
    if (isset($input['reveal_email_text'])) {
        $sanitized['reveal_email_text'] = sanitize_text_field($input['reveal_email_text']);
    }

    return $sanitized;
}

/**
 * Initialize settings for the Protect My Infos plugin.
 */
function yw_protect_my_infos_settings_init() {
    // Register the settings with sanitization callbacks
    register_setting(
        'yw_protect_my_infos_options_group',
        'yw_protect_my_infos_options',
        array(
            'type' => 'array',
            'sanitize_callback' => 'yw_protect_my_infos_sanitize_options',
        )
    );

    // General Settings Section
    add_settings_section(
        'yw_protect_my_infos_general_section',
        '',
        'yw_protect_my_infos_general_section_callback',
        'yw_protect_my_infos_general'
    );
    
    // Register fields for General Settings
    add_settings_field(
        'yw_protect_phone_numbers',
        esc_html__('Protect Phone Numbers', 'yw-protect-my-infos'),
        'yw_protect_my_infos_render_phone_numbers',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'protect_emails',
        esc_html__('Protect Emails', 'yw-protect-my-infos'),
        'yw_protect_my_infos_render_emails',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'show_icons',
        esc_html__('Show Icons', 'yw-protect-my-infos'),
        'yw_protect_my_infos_render_show_icons',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'text_color',
        esc_html__('Text Color', 'yw-protect-my-infos'),
        'yw_protect_my_infos_render_text_color',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'icons_color',
        esc_html__('Icons Color', 'yw-protect-my-infos'),
        'yw_protect_my_infos_render_icons_color',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    // Obfuscation Settings Section
    add_settings_section(
        'yw_protect_my_infos_obfuscation_section',
        '',
        'yw_protect_my_infos_obfuscation_section_callback',
        'yw_protect_my_infos_obfuscation'
    );
    

    // Register fields for Obfuscation Settings
    add_settings_field(
        'enable_obfuscation',
        esc_html__('Enable Obfuscation', 'yw-protect-my-infos'),
        'yw_protect_my_infos_render_enable_obfuscation',
        'yw_protect_my_infos_obfuscation',
        'yw_protect_my_infos_obfuscation_section'
    );

    add_settings_field(
        'yw-obfuscation_type',
        esc_html__('Obfuscation Type', 'yw-protect-my-infos'),
        'yw_protect_my_infos_render_obfuscation_type',
        'yw_protect_my_infos_obfuscation',
        'yw_protect_my_infos_obfuscation_section'
    );

}
add_action('admin_init', 'yw_protect_my_infos_settings_init');

/**
 * Callback for General Settings Section.
 */
function yw_protect_my_infos_general_section_callback() {
    echo '<p>' . esc_html__('Configure general settings for Protect My Infos.', 'yw-protect-my-infos') . '</p>';
}

/**
 * Callback for Obfuscation Settings Section.
 */
function yw_protect_my_infos_obfuscation_section_callback() {
    echo '<p>' . esc_html__('Configure obfuscation settings for Protect My Infos.', 'yw-protect-my-infos') . '</p>';
}
