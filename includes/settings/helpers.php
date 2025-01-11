<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Render functions for individual settings fields
function protect_phone_numbers_render() {
    $options = get_option('protect_my_infos_options');
    ?>
    <input type="checkbox" name="protect_my_infos_options[protect_phone_numbers]" <?php checked(isset($options['protect_phone_numbers']), 1); ?> value="1">
    <?php
}

function protect_emails_render() {
    $options = get_option('protect_my_infos_options');
    ?>
    <input type="checkbox" name="protect_my_infos_options[protect_emails]" <?php checked(isset($options['protect_emails']), 1); ?> value="1">
    <?php
}

function show_icons_render() {
    $options = get_option('protect_my_infos_options');
    ?>
    <input type="checkbox" name="protect_my_infos_options[show_icons]" <?php checked(isset($options['show_icons']), 1); ?> value="1">
    <?php
}

function text_color_render() {
    $options = get_option('protect_my_infos_options');
    ?>
    <input type="text" name="protect_my_infos_options[text_color]" id="text_color" value="<?php echo isset($options['text_color']) ? esc_attr($options['text_color']) : '#000000'; ?>" class="my-color-field" data-default-color="#000000">
    <?php
}

function icons_color_render() {
    $options = get_option('protect_my_infos_options');
    ?>
    <input type="text" name="protect_my_infos_options[icons_color]" value="<?php echo isset($options['icons_color']) ? esc_attr($options['icons_color']) : '#000000'; ?>" class="my-color-field" data-default-color="#000000">
    <?php
}

function enable_obfuscation_render() {
    $options = get_option('protect_my_infos_options');
    ?>
    <input type="checkbox" name="protect_my_infos_options[enable_obfuscation]" <?php checked(isset($options['enable_obfuscation']), 1); ?> value="1">
    <?php
}

function obfuscation_type_render() {
    $options = get_option('protect_my_infos_options');
    $selected = isset($options['obfuscation_type']) ? $options['obfuscation_type'] : 'placeholder';
    ?>
    <select name="protect_my_infos_options[obfuscation_type]" id="obfuscation_type">
        <option value="placeholder" <?php selected($selected, 'placeholder'); ?>><?php esc_html_e('With placeholder text', 'protect-my-infos'); ?></option>
        <option value="blurred" <?php selected($selected, 'blurred'); ?>><?php esc_html_e('With blurred letters/numbers', 'protect-my-infos'); ?></option>
    </select>
    <?php
}

function blur_mode_render() {
    $options = get_option('protect_my_infos_options');
    $selected = isset($options['blur_mode']) ? $options['blur_mode'] : 'full';
    ?>
    <select name="protect_my_infos_options[blur_mode]" id="blur_mode">
        <option value="full" <?php selected($selected, 'full'); ?>><?php esc_html_e('Blur entire data', 'protect-my-infos'); ?></option>
        <option value="center" <?php selected($selected, 'center'); ?>><?php esc_html_e('Blur only the center', 'protect-my-infos'); ?></option>
        <option value="first_half" <?php selected($selected, 'first_half'); ?>><?php esc_html_e('Blur only the first half', 'protect-my-infos'); ?></option>
        <option value="second_half" <?php selected($selected, 'second_half'); ?>><?php esc_html_e('Blur only the second half', 'protect-my-infos'); ?></option>
    </select>
    <?php
}

function protect_my_infos_enqueue_paypal($hook_suffix) {
    if ($hook_suffix === 'toplevel_page_protect-my-infos') {
        wp_register_script(
            'paypal-sdk',
            'https://www.paypalobjects.com/donate/sdk/donate-sdk.js',
            array(),
            $plugin_version,
            true
        );
        wp_enqueue_script('paypal-sdk');
    }
}