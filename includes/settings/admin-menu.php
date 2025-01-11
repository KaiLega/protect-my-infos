<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Add admin menu for the plugin
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
add_action('admin_menu', 'protect_my_infos_add_admin_menu');

// Render the settings page
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
                    <li><a href="#how-to-use"><?php esc_html_e('How to use it', 'protect-my-infos'); ?></a></li>
                    <li><a href="#support-author"><?php esc_html_e('Support the Author', 'protect-my-infos'); ?></a></li>
                </ul>
            </div>

            <!-- Single Form for All Settings -->
            <form id="protect-my-infos-settings-form" method="post" class="settings-content">
                <?php wp_nonce_field('protect_my_infos_nonce_action', 'protect_my_infos_nonce_field'); ?>
                <div id="general-settings" class="settings-section active">
                    <h2><?php esc_html_e('General Settings', 'protect-my-infos'); ?></h2>
                    <?php do_settings_sections('protect_my_infos_general'); ?>
                    <?php submit_button(__('Save Settings', 'protect-my-infos'), 'primary', 'submit', true); ?>
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
                                <td>
                                    <input 
                                        type="text" 
                                        name="protect_my_infos_options[reveal_phone_text]" 
                                        value="<?php echo isset($options['reveal_phone_text']) ? esc_attr($options['reveal_phone_text']) : ''; ?>" 
                                        placeholder="<?php esc_html_e('- Click to reveal the phone number -', 'protect-my-infos'); ?>"
                                        class="wide-input"
                                    >
                                </td>
                            </tr>
                            <tr class="reveal-option" <?php echo $options['obfuscation_type'] === 'placeholder' ? '' : 'style="display:none;"'; ?>>
                                <th scope="row"><?php esc_html_e('Custom Email Reveal Text', 'protect-my-infos'); ?></th>
                                <td>
                                    <input 
                                        type="text" 
                                        name="protect_my_infos_options[reveal_email_text]" 
                                        value="<?php echo isset($options['reveal_email_text']) ? esc_attr($options['reveal_email_text']) : ''; ?>" 
                                        placeholder="<?php esc_html_e('- Click to reveal the email -', 'protect-my-infos'); ?>" 
                                        class="wide-input"
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php submit_button(__('Save Settings', 'protect-my-infos'), 'primary', 'submit', true); ?>
                </div>

                    <!-- Advanced-settings section -->
                    <div id="advanced-settings" class="settings-section">
                        <h2><?php esc_html_e('Advanced Settings', 'protect-my-infos'); ?></h2>
                        <p><?php esc_html_e('New features coming soon...', 'protect-my-infos'); ?></p>
                    </div>

                    <!-- How to use it section -->
                    <div id="how-to-use" class="settings-section">
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
                    <div id="support-author" class="settings-section">
                        <h2><?php esc_html_e('Support the Author', 'protect-my-infos'); ?></h2>
                        <p><?php esc_html_e('If you like this plugin and would like to support me, please consider making a donation!', 'protect-my-infos'); ?></p>

                        <!-- PayPal Donation -->
                        <div class="donation-option">
                            <h3><?php esc_html_e('Donate with PayPal', 'protect-my-infos'); ?></h3>
                            <div id="donate-button-container">
                                <div id="donate-button"></div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    if (typeof PayPal !== 'undefined' && PayPal.Donation) {
                                        PayPal.Donation.Button({
                                            env: 'production',
                                            hosted_button_id: '87SXE2YJQAUWE',
                                            image: {
                                                src: 'https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif',
                                                alt: 'Donate with PayPal button',
                                                title: 'PayPal - The safer, easier way to pay online!'
                                            }
                                        }).render('#donate-button');
                                    } else {
                                        console.error('PayPal SDK not loaded.');
                                    }
                                });
                            </script>
                        </div>

                        <!-- Bitcoin Donation -->
                        <div class="donation-option">
                            <h3><?php esc_html_e('Donate with Bitcoin', 'protect-my-infos'); ?></h3>
                            <p><?php esc_html_e('Scan the QR code or use this address to donate:', 'protect-my-infos'); ?></p>
                            <p><strong>1CCR8p61GnGQaeKGfrhneewnxAqKDgxEZp</strong></p>
                            <div class="qr-code">
                                <img 
                                    src="<?php echo esc_url(add_query_arg(array(
                                        'image' => 'qr-code',
                                        'nonce' => wp_create_nonce('protect_my_infos_image_nonce')
                                    ), home_url('/'))); ?>" 
                                    alt="<?php esc_attr_e('Bitcoin QR Code', 'protect-my-infos'); ?>" 
                                    style="width: 80px; height: 80px;"
                                >
                            </div>
                        </div>
                    </div>
                    <div id="save-status"></div>
            </form>

            <!-- Right Column -->
            <div class="column-right">
                <div class="right-image">
                    <a href="https://www.yugaweb.com/" target="_blank">
                        <img 
                            src="<?php echo esc_url(add_query_arg(array(
                                'image' => 'banner',
                                'nonce' => wp_create_nonce('protect_my_infos_image_nonce')
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