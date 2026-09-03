<?php

/**
 * Copyright (c) 2024–present Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Sanitization callback for plugin options.
 */
function yw_protect_my_infos_sanitize_options($input) {
    // Define an empty array for sanitized values
    $sanitized = array();

    // Phone
    if (isset($input['yw_protect_phone_numbers'])) {
        $sanitized['yw_protect_phone_numbers'] = (int)$input['yw_protect_phone_numbers'];
    } elseif (isset($input['protect_phone_numbers'])) {
        $sanitized['yw_protect_phone_numbers'] = (int)$input['protect_phone_numbers'];
    }

    // Email
    if (isset($input['yw_protect_emails'])) {
        $sanitized['yw_protect_emails'] = (int)$input['yw_protect_emails'];
    } elseif (isset($input['protect_emails'])) {
        $sanitized['yw_protect_emails'] = (int)$input['protect_emails'];
    }

    // Sanitize each option individually
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

    $obfuscation_type = isset($input['yw-obfuscation_type']) ? sanitize_key($input['yw-obfuscation_type']) : 'placeholder';
    $sanitized['yw-obfuscation_type'] = in_array($obfuscation_type, array('placeholder', 'blurred'), true)
        ? $obfuscation_type
        : 'placeholder';

    $blur_mode = isset($input['blur_mode']) ? sanitize_key($input['blur_mode']) : 'full';
    $sanitized['blur_mode'] = in_array($blur_mode, array('full', 'center', 'first_half', 'second_half'), true)
        ? $blur_mode
        : 'full';

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
        esc_html__('Protect Phone Numbers', 'protect-my-infos'),
        'yw_protect_my_infos_render_phone_numbers',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'yw_protect_emails',
        esc_html__('Protect Emails', 'protect-my-infos'),
        'yw_protect_my_infos_render_emails',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'show_icons',
        esc_html__('Show Icons', 'protect-my-infos'),
        'yw_protect_my_infos_render_show_icons',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'text_color',
        esc_html__('Text Color', 'protect-my-infos'),
        'yw_protect_my_infos_render_text_color',
        'yw_protect_my_infos_general',
        'yw_protect_my_infos_general_section'
    );

    add_settings_field(
        'icons_color',
        esc_html__('Icons Color', 'protect-my-infos'),
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
        esc_html__('Enable Obfuscation', 'protect-my-infos'),
        'yw_protect_my_infos_render_enable_obfuscation',
        'yw_protect_my_infos_obfuscation',
        'yw_protect_my_infos_obfuscation_section'
    );

    add_settings_field(
        'yw-obfuscation_type',
        esc_html__('Obfuscation Type', 'protect-my-infos'),
        'yw_protect_my_infos_render_obfuscation_type',
        'yw_protect_my_infos_obfuscation',
        'yw_protect_my_infos_obfuscation_section'
    );

}
add_action('admin_init', 'yw_protect_my_infos_settings_init');

add_action('admin_init', function () {
    $opts = get_option('yw_protect_my_infos_options', []);
    $changed = false;

    if (!isset($opts['yw_protect_phone_numbers']) && isset($opts['protect_phone_numbers'])) {
        $opts['yw_protect_phone_numbers'] = intval($opts['protect_phone_numbers']);
        $changed = true;
    }
    if (!isset($opts['yw_protect_emails']) && isset($opts['protect_emails'])) {
        $opts['yw_protect_emails'] = intval($opts['protect_emails']);
        $changed = true;
    }

    if ($changed) {
        // opzionale “pulizia”:
        // unset($opts['protect_phone_numbers'], $opts['protect_emails']);
        update_option('yw_protect_my_infos_options', $opts);
    }
});

/**
 * Callback for General Settings Section.
 */
function yw_protect_my_infos_general_section_callback() {
    echo '<p>' . esc_html__('Configure general settings for Protect My Infos.', 'protect-my-infos') . '</p>';
}

/**
 * Callback for Obfuscation Settings Section.
 */
function yw_protect_my_infos_obfuscation_section_callback() {
    echo '<p>' . esc_html__('Configure obfuscation settings for Protect My Infos.', 'protect-my-infos') . '</p>';
}
