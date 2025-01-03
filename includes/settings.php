<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Handles AJAX save settings request
function protect_my_infos_ajax_save_settings() {
    // Verify nonce for security
    if (!check_ajax_referer('protect_my_infos_nonce', 'security', false)) {
        wp_send_json_error(esc_html__('Invalid nonce.', 'protect-my-infos'));
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(esc_html__('Permission denied.', 'protect-my-infos'));
        return;
    }

    // Process and validate POST data
    if (!isset($_POST['options'])) {
        wp_send_json_error(esc_html__('Invalid options structure.', 'protect-my-infos'));
        return;
    }

    parse_str($_POST['options'], $parsed_options);

    if (empty($parsed_options)) {
        wp_send_json_error(esc_html__('No valid options provided.', 'protect-my-infos'));
        return;
    }

    if (!is_array($parsed_options) || !isset($parsed_options['protect_my_infos_options'])) {
        wp_send_json_error(esc_html__('Invalid options structure.', 'protect-my-infos'));
        return;
    }

    $new_options = array_map('sanitize_text_field', $parsed_options['protect_my_infos_options']);

    // Save the new options
    update_option('protect_my_infos_options', $new_options);

    // Return success response
    wp_send_json_success(esc_html__('Settings saved successfully.', 'protect-my-infos'));
}

// Add admin menu for plugin settings
add_action('wp_ajax_protect_my_infos_save_settings', 'protect_my_infos_ajax_save_settings');

function protect_my_infos_add_admin_menu() {
    add_menu_page(
        esc_html__('Protect My Infos', 'protect-my-infos'),
        esc_html__('Protect My Infos', 'protect-my-infos'),
        'manage_options',
        'protect-my-infos',
        'protect_my_infos_options_page',
        'dashicons-shield-alt',
        80
    );
}

// Render the settings page
add_action('admin_menu', 'protect_my_infos_add_admin_menu');

function protect_my_infos_options_page() {
    $options = get_option('protect_my_infos_options');
    ?>
    <div class="wrap protect-my-infos-settings-page">
        <!-- Include the header -->
        <?php include plugin_dir_path(__FILE__) . 'header.php'; ?>

        <!-- Admin Notifications -->
        <div class="admin-notifications">
            <?php if (function_exists('get_plugin_updates') && $updates = get_plugin_updates()) : ?>
                <div class="update-nag">
                    <?php esc_html_e('Plugin updates are available.', 'protect-my-infos'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="settings-container">
            <!-- Sidebar Navigation -->
            <div class="sidebar-menu">
                <ul>
                    <li class="active"><a href="#general-settings"><?php esc_html_e('General Settings', 'protect-my-infos'); ?></a></li>
                    <li><a href="#obfuscation-settings"><?php esc_html_e('Obfuscation', 'protect-my-infos'); ?></a></li>
                    <li><a href="#advanced-settings"><?php esc_html_e('Advanced Settings', 'protect-my-infos'); ?></a></li>
                    <li><a href="#support-author"><?php esc_html_e('Support the Author', 'protect-my-infos'); ?></a></li>
                </ul>
            </div>

            <!-- Single Form for All Settings -->
            <form id="protect-my-infos-settings-form" method="post" class="settings-content">
                <div id="general-settings" class="settings-section active">
                    <h2><?php esc_html_e('General Settings', 'protect-my-infos'); ?></h2>
                    <?php do_settings_sections('protect_my_infos_general'); ?>
                </div>

                <div id="obfuscation-settings" class="settings-section">
                    <h2><?php esc_html_e('Obfuscation Settings', 'protect-my-infos'); ?></h2>
                    <?php do_settings_sections('protect_my_infos_obfuscation'); ?>

                    <!-- Blur Mode Field -->
                    <table class="form-table">
                        <tbody>
                            <tr class="blur-mode-option" <?php echo $options['obfuscation_type'] === 'blurred' ? '' : 'style="display:none;"'; ?>>
                                <th scope="row"><?php esc_html_e('Blur Mode', 'protect-my-infos'); ?></th>
                                <td><?php blur_mode_render(); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Conditional Fields for "placeholder" -->
                    <table class="form-table">
                        <tbody>
                            <tr class="reveal-option" <?php echo $options['obfuscation_type'] === 'placeholder' ? '' : 'style="display:none;"'; ?>>
                                <th scope="row"><?php esc_html_e('Custom Phone Reveal Text', 'protect-my-infos'); ?></th>
                                <td><input type="text" name="protect_my_infos_options[reveal_phone_text]" value="<?php echo isset($options['reveal_phone_text']) ? esc_attr($options['reveal_phone_text']) : ''; ?>" placeholder="<?php esc_html_e('- Click to reveal the phone number -', 'protect-my-infos'); ?>"></td>
                            </tr>
                            <tr class="reveal-option" <?php echo $options['obfuscation_type'] === 'placeholder' ? '' : 'style="display:none;"'; ?>>
                                <th scope="row"><?php esc_html_e('Custom Email Reveal Text', 'protect-my-infos'); ?></th>
                                <td><input type="text" name="protect_my_infos_options[reveal_email_text]" value="<?php echo isset($options['reveal_email_text']) ? esc_attr($options['reveal_email_text']) : ''; ?>" placeholder="<?php esc_html_e('- Click to reveal the email address -', 'protect-my-infos'); ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Advanced-settings section -->

                <div id="advanced-settings" class="settings-section">
                    <h2><?php esc_html_e('Advanced Settings', 'protect-my-infos'); ?></h2>
                    <p><?php esc_html_e('New features coming soon...', 'protect-my-infos'); ?></p>
                </div>

                <!-- Support Author section -->

                <div id="support-author" class="settings-section">
                    <h2><?php esc_html_e('Support the Author', 'protect-my-infos'); ?></h2>
                    <p><?php esc_html_e('If you like this plugin and would like to support me, please consider making a donation!', 'protect-my-infos'); ?></p>

                    <!-- PayPal Donation -->
                    <div class="donation-option">
                        <h3><?php esc_html_e('Donate with PayPal', 'protect-my-infos'); ?></h3>
                        <div id="donate-button-container">
                            <div id="donate-button"></div>
                            <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
                            <script>
                                PayPal.Donation.Button({
                                    env: 'production',
                                    hosted_button_id: '87SXE2YJQAUWE',
                                    image: {
                                        src: 'https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif',
                                        alt: 'Donate with PayPal button',
                                        title: 'PayPal - The safer, easier way to pay online!'
                                    }
                                }).render('#donate-button');
                            </script>
                        </div>
                    </div>

                    <!-- Bitcoin Donation -->
                    <div class="donation-option">
                        <h3><?php esc_html_e('Donate with Bitcoin', 'protect-my-infos'); ?></h3>
                        <p><?php esc_html_e('Scan the QR code or use this address to donate:', 'protect-my-infos'); ?></p>
                        <p><strong>1CCR8p61GnGQaeKGfrhneewnxAqKDgxEZp</strong></p>
                        <div class="qr-code" style="width: 100%; height: 100%; box-shadow: var(--shadow-elevation1); opacity: 1; border-radius: 4px; padding: var(--space-2xs); text-align: center;">
                            <?php
                            $svg_path = plugin_dir_path(__FILE__) . '../images/qr-code.svg';
                            if (file_exists($svg_path)) {
                                echo file_get_contents($svg_path);
                            } else {
                                esc_html_e('SVG not found.', 'protect-my-infos');
                            }
                            ?>
                        </div>
                    </div>
                </div>


        <?php submit_button(__('Save Settings', 'protect-my-infos'), 'primary', 'submit', true); ?>
        <div id="save-status"></div>
        </form>

        <!-- Advertisement Column -->
        <div class="ad-column">
        <div class="ad-banner">
        <a href="https://www.yugaweb.com/" target="_blank">
        <img src="<?php echo esc_url(plugins_url('images/banner.png', dirname(__FILE__))); ?>" alt="<?php esc_attr_e('Ad Banner', 'protect-my-infos'); ?>" style="width: 100%;">
        </a>
        </div>
        </div>
        </div>
    </div>
    <?php
}

// Initialize plugin settings
function protect_my_infos_settings_init() {
    register_setting('protect_my_infos_options_group', 'protect_my_infos_options');

    // General Settings Section
    add_settings_section(
        'protect_my_infos_general_section',
        '',
        'protect_my_infos_general_section_callback',
        'protect_my_infos_general'
    );

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

// General Section Callback
function protect_my_infos_general_section_callback() {
    // Instructions or description for the General settings section
}

// Obfuscation Section Callback
function protect_my_infos_obfuscation_section_callback() {
    // Instructions or description for the Obfuscation settings section
}

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
