<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Render functions for individual settings fields
function yw_protect_my_infos_render_phone_numbers() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input type="checkbox" name="yw_protect_my_infos_options[protect_phone_numbers]" <?php checked(isset($options['protect_phone_numbers']), 1); ?> value="1">
    <?php
}

function yw_protect_my_infos_render_emails() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input type="checkbox" name="yw_protect_my_infos_options[protect_emails]" <?php checked(isset($options['protect_emails']), 1); ?> value="1">
    <?php
}

function yw_protect_my_infos_render_show_icons() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input type="checkbox" name="yw_protect_my_infos_options[show_icons]" <?php checked(isset($options['show_icons']), 1); ?> value="1">
    <?php
}

function yw_protect_my_infos_render_text_color() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input type="text" name="yw_protect_my_infos_options[text_color]" id="text_color" value="<?php echo isset($options['text_color']) ? esc_attr($options['text_color']) : '#000000'; ?>" class="yw-color-field" data-default-color="#000000">
    <?php
}

function yw_protect_my_infos_render_icons_color() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input type="text" name="yw_protect_my_infos_options[icons_color]" value="<?php echo isset($options['icons_color']) ? esc_attr($options['icons_color']) : '#000000'; ?>" class="yw-color-field" data-default-color="#000000">
    <?php
}

function yw_protect_my_infos_render_enable_obfuscation() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input type="checkbox" name="yw_protect_my_infos_options[enable_obfuscation]" <?php checked(isset($options['enable_obfuscation']), 1); ?> value="1">
    <?php
}

function yw_protect_my_infos_render_obfuscation_type() {
    $options = get_option('yw_protect_my_infos_options');
    $selected = isset($options['yw-obfuscation_type']) ? $options['yw-obfuscation_type'] : 'placeholder';
    ?>
    <select name="yw_protect_my_infos_options[yw-obfuscation_type]" id="yw-obfuscation_type">
        <option value="placeholder" <?php selected($selected, 'placeholder'); ?>><?php esc_html_e('With placeholder text', 'yw-protect-my-infos'); ?></option>
        <option value="blurred" <?php selected($selected, 'blurred'); ?>><?php esc_html_e('With blurred letters/numbers', 'yw-protect-my-infos'); ?></option>
    </select>
    <?php
}

function yw_protect_my_infos_render_blur_mode() {
    $options = get_option('yw_protect_my_infos_options');
    $selected = isset($options['blur_mode']) ? $options['blur_mode'] : 'full';
    ?>
    <select name="yw_protect_my_infos_options[blur_mode]" id="blur_mode">
        <option value="full" <?php selected($selected, 'full'); ?>><?php esc_html_e('Blur entire data', 'yw-protect-my-infos'); ?></option>
        <option value="center" <?php selected($selected, 'center'); ?>><?php esc_html_e('Blur only the center', 'yw-protect-my-infos'); ?></option>
        <option value="first_half" <?php selected($selected, 'first_half'); ?>><?php esc_html_e('Blur only the first half', 'yw-protect-my-infos'); ?></option>
        <option value="second_half" <?php selected($selected, 'second_half'); ?>><?php esc_html_e('Blur only the second half', 'yw-protect-my-infos'); ?></option>
    </select>
    <?php
}

function yw_protect_my_infos_render_reveal_option() {
    $options = get_option('yw_protect_my_infos_options');
    $placeholder = isset($options['reveal_option']) ? esc_attr($options['reveal_option']) : '';
    ?>
    <input type="text" name="yw_protect_my_infos_options[reveal_option]" value="<?php echo $placeholder; ?>" placeholder="<?php esc_html_e('Enter custom text...', 'yw-protect-my-infos'); ?>" />
    <?php
}

function yw_protect_my_infos_render_reveal_phone_text() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input 
        type="text" 
        name="yw_protect_my_infos_options[reveal_phone_text]" 
        value="<?php echo isset($options['reveal_phone_text']) ? esc_attr($options['reveal_phone_text']) : ''; ?>" 
        placeholder="<?php esc_html_e('- Click to reveal the phone number -', 'yw-protect-my-infos'); ?>" 
        class="yw-wide-input"
    >
    <?php
}

function yw_protect_my_infos_render_reveal_email_text() {
    $options = get_option('yw_protect_my_infos_options');
    ?>
    <input 
        type="text" 
        name="yw_protect_my_infos_options[reveal_email_text]" 
        value="<?php echo isset($options['reveal_email_text']) ? esc_attr($options['reveal_email_text']) : ''; ?>" 
        placeholder="<?php esc_html_e('- Click to reveal the email -', 'yw-protect-my-infos'); ?>" 
        class="yw-wide-input"
    >
    <?php
}