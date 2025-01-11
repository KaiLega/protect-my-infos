<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

function protect_my_infos_settings_init() {
    register_setting('protect_my_infos_options_group', 'protect_my_infos_options');

    // General Settings Section
    add_settings_section(
        'protect_my_infos_general_section',
        '',
        'protect_my_infos_general_section_callback',
        'protect_my_infos_general'
    );

    // Register fields for General Settings
    add_settings_field(
        'protect_phone_numbers',
        esc_html__('Protect Phone Numbers', 'protect-my-infos'),
        'protect_phone_numbers_render',
        'protect_my_infos_general',
        'protect_my_infos_general_section'
    );

    add_settings_field(
        'protect_emails',
        esc_html__('Protect Emails', 'protect-my-infos'),
        'protect_emails_render',
        'protect_my_infos_general',
        'protect_my_infos_general_section'
    );

    add_settings_field(
        'show_icons',
        esc_html__('Show Icons', 'protect-my-infos'),
        'show_icons_render',
        'protect_my_infos_general',
        'protect_my_infos_general_section'
    );

    add_settings_field(
        'text_color',
        esc_html__('Text Color', 'protect-my-infos'),
        'text_color_render',
        'protect_my_infos_general',
        'protect_my_infos_general_section'
    );

    add_settings_field(
        'icons_color',
        esc_html__('Icons Color', 'protect-my-infos'),
        'icons_color_render',
        'protect_my_infos_general',
        'protect_my_infos_general_section'
    );

    // Obfuscation Settings Section
    add_settings_section(
        'protect_my_infos_obfuscation_section',
        '',
        'protect_my_infos_obfuscation_section_callback',
        'protect_my_infos_obfuscation'
    );

    // Register fields for Obfuscation Settings
    add_settings_field(
        'enable_obfuscation',
        esc_html__('Enable Obfuscation', 'protect-my-infos'),
        'enable_obfuscation_render',
        'protect_my_infos_obfuscation',
        'protect_my_infos_obfuscation_section'
    );

    add_settings_field(
        'obfuscation_type',
        esc_html__('Obfuscation Type', 'protect-my-infos'),
        'obfuscation_type_render',
        'protect_my_infos_obfuscation',
        'protect_my_infos_obfuscation_section'
    );
}
add_action('admin_init', 'protect_my_infos_settings_init');

function protect_my_infos_general_section_callback() {
    echo '<p>' . esc_html__('Configure general settings for Protect My Infos.', 'protect-my-infos') . '</p>';
}

function protect_my_infos_obfuscation_section_callback() {
    echo '<p>' . esc_html__('Configure obfuscation settings for Protect My Infos.', 'protect-my-infos') . '</p>';
}
