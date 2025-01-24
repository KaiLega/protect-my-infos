<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Add admin menu for the plugin
function yw_protect_my_infos_add_admin_menu() {
    add_menu_page(
        esc_html__('Protect My Infos', 'protect-my-infos'),
        esc_html__('Protect My Infos', 'protect-my-infos'),
        'manage_options',
        'yw-protect-my-infos',
        'yw_protect_my_infos_options_page',
        'dashicons-shield-alt',
        80
    );
}
add_action('admin_menu', 'yw_protect_my_infos_add_admin_menu');

// Render the settings page
function yw_protect_my_infos_options_page() {
    $options = get_option('yw_protect_my_infos_options', array(
        'protect_phone_numbers' => 0,
        'protect_emails' => 0,
        'show_icons' => 0,
        'text_color' => '#000000',
        'icons_color' => '#000000',
        'enable_obfuscation' => 0,
        'yw-obfuscation_type' => 'placeholder',
    ));
    ?>
    <div class="wrap yw-protect-my-infos-settings-page">

        <!-- Include the header -->
        <?php include plugin_dir_path(__FILE__) . 'header.php'; ?>

        <!-- Admin Notifications -->
        <div class="yw-admin-notifications">
            <?php if (function_exists('get_plugin_updates') && $updates = get_plugin_updates()) : ?>
                <div class="update-nag">
                    <?php esc_html_e('Plugin updates are available.', 'protect-my-infos'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="yw-settings-container">
            
            <!-- Sidebar Navigation -->
            <div class="yw-sidebar-menu">
                <ul>
                    <li class="active"><a href="#yw-general-settings"><?php esc_html_e('General Settings', 'protect-my-infos'); ?></a></li>
                    <li><a href="#yw-obfuscation-settings"><?php esc_html_e('Obfuscation', 'protect-my-infos'); ?></a></li>
                    <li><a href="#yw-advanced-settings"><?php esc_html_e('Advanced Settings', 'protect-my-infos'); ?></a></li>
                    <li><a href="#yw-how-to-use"><?php esc_html_e('How to use it', 'protect-my-infos'); ?></a></li>
                    <li><a href="#yw-support-author"><?php esc_html_e('Support the Author', 'protect-my-infos'); ?></a></li>
                </ul>
            </div>

            <!-- Single Form for All Settings -->
            <form id="yw-protect-my-infos-settings-form" method="post" class="yw-settings-content">
                <input type="hidden" name="option_page" value="yw_protect_my_infos_options_group">
                <?php wp_nonce_field('yw_protect_my_infos_nonce_action', 'yw_protect_my_infos_nonce_field'); ?>

                 <!-- General Settings -->
                <div id="yw-general-settings" class="yw-settings-section active">
                    <h2><?php esc_html_e('General Settings', 'protect-my-infos'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e('Protect Phone Numbers', 'protect-my-infos'); ?></th>
                            <td>
                                <input type="checkbox" name="yw_protect_my_infos_options[protect_phone_numbers]" value="1" <?php checked(1, $options['protect_phone_numbers'], true); ?> />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Protect Emails', 'protect-my-infos'); ?></th>
                            <td>
                                <input type="checkbox" name="yw_protect_my_infos_options[protect_emails]" value="1" <?php checked(1, $options['protect_emails'], true); ?> />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Show Icons', 'protect-my-infos'); ?></th>
                            <td>
                                <input type="checkbox" name="yw_protect_my_infos_options[show_icons]" value="1" <?php checked(1, $options['show_icons'], true); ?> />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Text Color', 'protect-my-infos'); ?></th>
                            <td>
                                <input type="text" name="yw_protect_my_infos_options[text_color]" value="<?php echo esc_attr($options['text_color']); ?>" class="yw-color-field" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Icons Color', 'protect-my-infos'); ?></th>
                            <td>
                                <input type="text" name="yw_protect_my_infos_options[icons_color]" value="<?php echo esc_attr($options['icons_color']); ?>" class="yw-color-field" />
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(__('Save Settings', 'protect-my-infos'), 'primary', 'submit', true); ?>
                </div>

                <!-- Obfuscation Settings -->
                <div id="yw-obfuscation-settings" class="yw-settings-section">
                    <h2><?php esc_html_e('Obfuscation Settings', 'protect-my-infos'); ?></h2>
                    <?php do_settings_sections('yw_protect_my_infos_obfuscation'); ?>

                    <!-- Blur Mode Field -->
                    <table class="form-table">
                        <tbody>
                            <tr class="yw-blur-mode-option" style="display: none;">
                                <th scope="row"><?php esc_html_e('Blur Mode', 'protect-my-infos'); ?></th>
                                <td><?php yw_protect_my_infos_render_blur_mode(); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="form-table">
                        <tbody>
                            <tr class="yw-reveal-option" style="display: none;">
                                <th scope="row"><?php esc_html_e('Custom Phone Reveal Text', 'protect-my-infos'); ?></th>
                                <td><?php yw_protect_my_infos_render_reveal_phone_text(); ?></td>
                            </tr>
                            <tr class="yw-reveal-option" style="display: none;">
                                <th scope="row"><?php esc_html_e('Custom Email Reveal Text', 'protect-my-infos'); ?></th>
                                <td><?php yw_protect_my_infos_render_reveal_email_text(); ?></td>
                            </tr>
                        </tbody>
                    </table>


                    <?php submit_button(__('Save Settings', 'protect-my-infos'), 'primary', 'submit', true); ?>
                </div>

                <!-- Advanced-settings section -->
                <div id="yw-advanced-settings" class="yw-settings-section">
                    <h2><?php esc_html_e('Advanced Settings', 'protect-my-infos'); ?></h2>
                    <p><?php esc_html_e('New features coming soon...', 'protect-my-infos'); ?></p>
                </div>

                <!-- How to use it section -->
                <div id="yw-how-to-use" class="yw-settings-section">
                    <h2><?php esc_html_e('How to use Protect My Infos', 'protect-my-infos'); ?></h2>
                    <p><?php esc_html_e('To use Protect My Infos, follow these steps:', 'protect-my-infos'); ?></p>
                    <ol>
                        <li><?php esc_html_e('In General Settings, choose the data you want to protect: phone numbers, email addresses, or both.', 'protect-my-infos'); ?></li>
                        <li><?php esc_html_e('In Obfuscation, set your preferred obfuscation type and customization options.', 'protect-my-infos'); ?></li>
                        <li><?php esc_html_e('Use the shortcode in your posts or pages to protect specific information:', 'protect-my-infos'); ?></li>
                        <pre>
[protect_my_infos type="email" value="youremail@example.com"]
[protect_my_infos type="phone" value="+1234567890"]
                        </pre>
                        <li><?php esc_html_e('Save your settings and test on your site to ensure proper functionality.', 'protect-my-infos'); ?></li>
                    </ol>
                </div>

                <!-- Support Author section -->
                <div id="yw-support-author" class="yw-settings-section">
                    <h2><?php esc_html_e('Support the Author', 'protect-my-infos'); ?></h2>
                    <p><?php esc_html_e('If you like this plugin and would like to support me, please consider making a donation!', 'protect-my-infos'); ?></p>

                    <!-- PayPal Donation -->
                    <div class="yw-donation-option">
                        <h3><?php esc_html_e('Donate with PayPal', 'protect-my-infos'); ?></h3>
                        <div id="yw-donate-button-container">
                            <div id="yw-donate-button"></div>
                        </div>
                    </div>

                    <!-- Bitcoin Donation -->
                    <div class="yw-donation-option">
                        <h3><?php esc_html_e('Donate with Bitcoin', 'protect-my-infos'); ?></h3>
                        <p><?php esc_html_e('Scan the QR code or use this address to donate:', 'protect-my-infos'); ?></p>
                        <p><strong>1CCR8p61GnGQaeKGfrhneewnxAqKDgxEZp</strong></p>
                        <div class="yw-qr-code">
                            <img 
                                src="<?php echo esc_url(add_query_arg(array(
                                    'image' => 'qr-code',
                                    'nonce' => wp_create_nonce('yw_protect_my_infos_image_nonce')
                                ), home_url('/'))); ?>" 
                                alt="<?php esc_attr_e('Bitcoin QR Code', 'protect-my-infos'); ?>" 
                                style="width: 80px; height: 80px;"
                            >
                        </div>
                    </div>
                </div>
                <div id="yw-save-status"></div>
            </form>

            <!-- Right Column -->
            <div class="yw-column-right">
                <div class="yw-right-image">
                    <a href="https://www.yugaweb.com/" target="_blank">
                        <img 
                            src="<?php echo esc_url(add_query_arg(array(
                                'image' => 'banner',
                                'nonce' => wp_create_nonce('yw_protect_my_infos_image_nonce')
                            ), home_url('/'))); ?>" 
                            alt="<?php esc_attr_e('Yuga Web Design banner', 'protect-my-infos'); ?>" 
                            style="width: 100%;"
                        />
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
