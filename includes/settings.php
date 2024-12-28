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
        wp_send_json_error(__('Invalid nonce.', 'protect-my-infos'));
        return;
    }
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Permission denied.', 'protect-my-infos'));
        return;
    }

    // Process and validate POST data
    if (!isset($_POST['options'])) {
        wp_send_json_error(__('Invalid options structure.', 'protect-my-infos'));
        return;
    }
    
    parse_str($_POST['options'], $parsed_options);
    
    if (empty($parsed_options)) {
        wp_send_json_error(__('No valid options provided.', 'protect-my-infos'));
        return;
    }
    
    if (!is_array($parsed_options) || !isset($parsed_options['protect_my_infos_options'])) {
        wp_send_json_error(__('Invalid options structure.', 'protect-my-infos'));
        return;
    }
    
    $new_options = array_map('sanitize_text_field', $parsed_options['protect_my_infos_options']);
    
    // Save the new options
    update_option('protect_my_infos_options', $new_options);
    
    // Return success response
    wp_send_json_success(__('Settings saved successfully.', 'protect-my-infos'));
}

// Add admin menu for plugin settings
add_action('wp_ajax_protect_my_infos_save_settings', 'protect_my_infos_ajax_save_settings');

function protect_my_infos_add_admin_menu() {
    add_menu_page(
                  __('Protect My Infos', 'protect-my-infos'),
                  __('Protect My Infos', 'protect-my-infos'),
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
        <?php _e('Plugin updates are available.', 'protect-my-infos'); ?>
    </div>
    <?php endif; ?>
    </div>
    
    <div class="settings-container">
    <!-- Sidebar Navigation -->
    <div class="sidebar-menu">
    <ul>
    <li class="active"><a href="#general-settings"><?php _e('General Settings', 'protect-my-infos'); ?></a></li>
    <li><a href="#obfuscation-settings"><?php _e('Obfuscation', 'protect-my-infos'); ?></a></li>
    <li><a href="#advanced-settings"><?php _e('Advanced Settings', 'protect-my-infos'); ?></a></li>
    <li><a href="#support-author"><?php _e('Support the Author', 'protect-my-infos'); ?></a></li>
    </ul>
    </div>
    
    <!-- Single Form for All Settings -->
        <form id="protect-my-infos-settings-form" method="post" class="settings-content">
        <div id="general-settings" class="settings-section active">
        <h2><?php _e('General Settings', 'protect-my-infos'); ?></h2>
    <?php do_settings_sections('protect_my_infos_general'); ?>
    </div>
    
    <div id="obfuscation-settings" class="settings-section">
    <h2><?php _e('Obfuscation Settings', 'protect-my-infos'); ?></h2>
    <?php do_settings_sections('protect_my_infos_obfuscation'); ?>
    
    <!-- Blur Mode Field -->
    <table class="form-table">
    <tbody>
    <tr class="blur-mode-option" <?php echo $options['obfuscation_type'] === 'blurred' ? '' : 'style="display:none;"'; ?>>
    <th scope="row"><?php _e('Blur Mode', 'protect-my-infos'); ?></th>
    <td><?php blur_mode_render(); ?></td>
    </tr>
    </tbody>
    </table>
    
    <!-- Conditional Fields for "placeholder" -->
        <table class="form-table">
        <tbody>
        <tr class="reveal-option" <?php echo $options['obfuscation_type'] === 'placeholder' ? '' : 'style="display:none;"'; ?>>
    <th scope="row"><?php _e('Custom Phone Reveal Text', 'protect-my-infos'); ?></th>
    <td><input type="text" name="protect_my_infos_options[reveal_phone_text]" value="<?php echo isset($options['reveal_phone_text']) ? esc_attr($options['reveal_phone_text']) : ''; ?>" placeholder="<?php _e('- Click to reveal the phone number -', 'protect-my-infos'); ?>"></td>
    </tr>
    <tr class="reveal-option" <?php echo $options['obfuscation_type'] === 'placeholder' ? '' : 'style="display:none;"'; ?>>
    <th scope="row"><?php _e('Custom Email Reveal Text', 'protect-my-infos'); ?></th>
    <td><input type="text" name="protect_my_infos_options[reveal_email_text]" value="<?php echo isset($options['reveal_email_text']) ? esc_attr($options['reveal_email_text']) : ''; ?>" placeholder="<?php _e('- Click to reveal the email address -', 'protect-my-infos'); ?>"></td>
    </tr>
    </tbody>
    </table>
    </div>
    
    <!-- Advanced-settings section -->

    <div id="advanced-settings" class="settings-section">
    <h2><?php _e('Advanced Settings', 'protect-my-infos'); ?></h2>
    <p><?php _e('New features coming soon...', 'protect-my-infos'); ?></p>
    </div>
    
    <!-- Support Author section -->

    <div id="support-author" class="settings-section">
        <h2><?php _e('Support the Author', 'protect-my-infos'); ?></h2>
        <p><?php _e('If you like this plugin and would like to support me, please consider making a donation!', 'protect-my-infos'); ?></p>
        
        <!-- PayPal Donation -->
        <div class="donation-option">
            <h3><?php _e('Donate with PayPal', 'protect-my-infos'); ?></h3>
            <div id="donate-button-container">
                <div id="donate-button"></div>
                <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
                <script>
                PayPal.Donation.Button({
                env:'production',
                hosted_button_id:'87SXE2YJQAUWE',
                image: {
                src:'https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif',
                alt:'Donate with PayPal button',
                title:'PayPal - The safer, easier way to pay online!',
                }
                }).render('#donate-button');
                </script>
            </div>

        </div>
        
        <!-- Bitcoin Donation -->
        <div class="donation-option">
            <h3><?php _e('Donate with Bitcoin', 'protect-my-infos'); ?></h3>
            <p><?php _e('Scan the QR code or use this address to donate:', 'protect-my-infos'); ?></p>
            <p><strong>1CCR8p61GnGQaeKGfrhneewnxAqKDgxEZp</strong></p>
            <div class="qr-code" style="width: 100%; height: 100%; box-shadow: var(--shadow-elevation1); opacity: 1; border-radius: 4px; padding: var(--space-2xs); text-align: center;">
                <svg height="80" width="80" viewBox="0 0 33 33"><path fill="#FFFFFF" d="M0,0 h33v33H0z" shape-rendering="crispEdges"></path><path fill="#000000" d="M0 0h7v1H0zM9 0h3v1H9zM14 0h1v1H14zM16 0h2v1H16zM22 0h3v1H22zM26,0 h7v1H26zM0 1h1v1H0zM6 1h1v1H6zM13 1h2v1H13zM16 1h1v1H16zM18 1h1v1H18zM20 1h2v1H20zM23 1h1v1H23zM26 1h1v1H26zM32,1 h1v1H32zM0 2h1v1H0zM2 2h3v1H2zM6 2h1v1H6zM11 2h2v1H11zM17 2h1v1H17zM20 2h1v1H20zM26 2h1v1H26zM28 2h3v1H28zM32,2 h1v1H32zM0 3h1v1H0zM2 3h3v1H2zM6 3h1v1H6zM9 3h1v1H9zM12 3h1v1H12zM14 3h2v1H14zM18 3h1v1H18zM20 3h3v1H20zM26 3h1v1H26zM28 3h3v1H28zM32,3 h1v1H32zM0 4h1v1H0zM2 4h3v1H2zM6 4h1v1H6zM8 4h1v1H8zM12 4h1v1H12zM15 4h3v1H15zM19 4h1v1H19zM21 4h1v1H21zM23 4h2v1H23zM26 4h1v1H26zM28 4h3v1H28zM32,4 h1v1H32zM0 5h1v1H0zM6 5h1v1H6zM9 5h1v1H9zM12 5h4v1H12zM18 5h2v1H18zM21 5h1v1H21zM24 5h1v1H24zM26 5h1v1H26zM32,5 h1v1H32zM0 6h7v1H0zM8 6h1v1H8zM10 6h1v1H10zM12 6h1v1H12zM14 6h1v1H14zM16 6h1v1H16zM18 6h1v1H18zM20 6h1v1H20zM22 6h1v1H22zM24 6h1v1H24zM26,6 h7v1H26zM8 7h2v1H8zM11 7h3v1H11zM15 7h1v1H15zM18 7h1v1H18zM20 7h1v1H20zM23 7h2v1H23zM2 8h2v1H2zM6 8h3v1H6zM12 8h1v1H12zM15 8h1v1H15zM18 8h4v1H18zM25 8h2v1H25zM28 8h1v1H28zM0 9h1v1H0zM2 9h4v1H2zM7 9h1v1H7zM16 9h4v1H16zM22 9h2v1H22zM25 9h1v1H25zM30 9h1v1H30zM32,9 h1v1H32zM6 10h1v1H6zM9 10h1v1H9zM11 10h1v1H11zM14 10h3v1H14zM18 10h1v1H18zM21 10h1v1H21zM23 10h2v1H23zM26,10 h7v1H26zM0 11h1v1H0zM3 11h1v1H3zM8 11h2v1H8zM12 11h2v1H12zM15 11h2v1H15zM19 11h2v1H19zM22 11h3v1H22zM28 11h2v1H28zM32,11 h1v1H32zM0 12h1v1H0zM3 12h4v1H3zM9 12h1v1H9zM13 12h1v1H13zM19 12h2v1H19zM22 12h1v1H22zM24 12h2v1H24zM27 12h2v1H27zM31 12h1v1H31zM0 13h1v1H0zM2 13h2v1H2zM7 13h4v1H7zM13 13h1v1H13zM15 13h3v1H15zM22 13h3v1H22zM27 13h1v1H27zM29 13h1v1H29zM31 13h1v1H31zM0 14h2v1H0zM6 14h3v1H6zM10 14h2v1H10zM14 14h2v1H14zM18 14h4v1H18zM24 14h1v1H24zM28 14h2v1H28zM31 14h1v1H31zM3 15h2v1H3zM7 15h1v1H7zM9 15h2v1H9zM13 15h2v1H13zM17 15h1v1H17zM20 15h2v1H20zM25 15h1v1H25zM29 15h2v1H29zM32,15 h1v1H32zM1 16h1v1H1zM5 16h2v1H5zM9 16h1v1H9zM11 16h4v1H11zM17 16h1v1H17zM19 16h1v1H19zM21 16h1v1H21zM23 16h4v1H23zM28 16h2v1H28zM32,16 h1v1H32zM0 17h1v1H0zM4 17h1v1H4zM7 17h3v1H7zM13 17h2v1H13zM17 17h3v1H17zM23 17h6v1H23zM32,17 h1v1H32zM0 18h4v1H0zM6 18h1v1H6zM10 18h2v1H10zM13 18h1v1H13zM16 18h1v1H16zM18 18h3v1H18zM22 18h1v1H22zM24 18h2v1H24zM27 18h5v1H27zM5 19h1v1H5zM8 19h2v1H8zM16 19h1v1H16zM22 19h2v1H22zM25 19h1v1H25zM27 19h2v1H27zM31,19 h2v1H31zM0 20h4v1H0zM6 20h2v1H6zM9 20h1v1H9zM11 20h1v1H11zM16 20h1v1H16zM20 20h2v1H20zM23 20h1v1H23zM27 20h2v1H27zM31 20h1v1H31zM0 21h1v1H0zM3 21h2v1H3zM8 21h3v1H8zM12 21h1v1H12zM15 21h1v1H15zM18 21h3v1H18zM25 21h2v1H25zM29 21h1v1H29zM31,21 h2v1H31zM2 22h2v1H2zM6 22h2v1H6zM10 22h2v1H10zM13 22h2v1H13zM16 22h2v1H16zM19 22h1v1H19zM23 22h1v1H23zM26,22 h7v1H26zM1 23h1v1H1zM3 23h1v1H3zM5 23h1v1H5zM8 23h1v1H8zM11 23h1v1H11zM14 23h1v1H14zM16 23h2v1H16zM19 23h1v1H19zM22 23h1v1H22zM25 23h5v1H25zM31,23 h2v1H31zM0 24h1v1H0zM2 24h9v1H2zM16 24h1v1H16zM21 24h8v1H21zM30 24h2v1H30zM8 25h2v1H8zM11 25h1v1H11zM16 25h5v1H16zM24 25h1v1H24zM28 25h3v1H28zM32,25 h1v1H32zM0 26h7v1H0zM8 26h2v1H8zM11 26h2v1H11zM15 26h1v1H15zM18 26h1v1H18zM20 26h1v1H20zM22 26h3v1H22zM26 26h1v1H26zM28 26h1v1H28zM0 27h1v1H0zM6 27h1v1H6zM10 27h1v1H10zM13 27h1v1H13zM16 27h3v1H16zM21 27h1v1H21zM23 27h2v1H23zM28 27h4v1H28zM0 28h1v1H0zM2 28h3v1H2zM6 28h1v1H6zM9 28h1v1H9zM11 28h2v1H11zM14 28h1v1H14zM20 28h1v1H20zM23 28h8v1H23zM32,28 h1v1H32zM0 29h1v1H0zM2 29h3v1H2zM6 29h1v1H6zM8 29h2v1H8zM12 29h3v1H12zM20 29h4v1H20zM25 29h1v1H25zM28 29h1v1H28zM30,29 h3v1H30zM0 30h1v1H0zM2 30h3v1H2zM6 30h1v1H6zM8 30h2v1H8zM11 30h2v1H11zM17 30h1v1H17zM19 30h2v1H19zM22 30h5v1H22zM29 30h1v1H29zM0 31h1v1H0zM6 31h1v1H6zM9 31h3v1H9zM13 31h1v1H13zM16 31h1v1H16zM18 31h2v1H18zM21 31h4v1H21zM26 31h3v1H26zM32,31 h1v1H32zM0 32h7v1H0zM9 32h1v1H9zM18 32h1v1H18zM20 32h4v1H20zM28 32h1v1H28z" shape-rendering="crispEdges"></path></svg>
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
                       __('Protect Phone Numbers', 'protect-my-infos'),
                       'protect_phone_numbers_render',
                       'protect_my_infos_general',
                       'protect_my_infos_general_section'
                       );
    
    add_settings_field(
                       'protect_emails',
                       __('Protect Emails', 'protect-my-infos'),
                       'protect_emails_render',
                       'protect_my_infos_general',
                       'protect_my_infos_general_section'
                       );
    
    add_settings_field(
                       'show_icons',
                       __('Show Icons', 'protect-my-infos'),
                       'show_icons_render',
                       'protect_my_infos_general',
                       'protect_my_infos_general_section'
                       );
    
    add_settings_field(
                       'text_color',
                       __('Text Color', 'protect-my-infos'),
                       'text_color_render',
                       'protect_my_infos_general',
                       'protect_my_infos_general_section'
                       );
    
    add_settings_field(
                       'icons_color',
                       __('Icons Color', 'protect-my-infos'),
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
                       __('Enable Obfuscation', 'protect-my-infos'),
                       'enable_obfuscation_render',
                       'protect_my_infos_obfuscation',
                       'protect_my_infos_obfuscation_section'
                       );
    
    add_settings_field(
                       'obfuscation_type',
                       __('Obfuscation Type', 'protect-my-infos'),
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
    <option value="placeholder" <?php selected($selected, 'placeholder'); ?>><?php _e('With placeholder text', 'protect-my-infos'); ?></option>
    <option value="blurred" <?php selected($selected, 'blurred'); ?>><?php _e('With blurred letters/numbers', 'protect-my-infos'); ?></option>
    </select>
    <?php
}

function blur_mode_render() {
    $options = get_option('protect_my_infos_options');
    $selected = isset($options['blur_mode']) ? $options['blur_mode'] : 'full';
    ?>
    <select name="protect_my_infos_options[blur_mode]" id="blur_mode">
    <option value="full" <?php selected($selected, 'full'); ?>><?php _e('Blur entire data', 'protect-my-infos'); ?></option>
    <option value="center" <?php selected($selected, 'center'); ?>><?php _e('Blur only the center', 'protect-my-infos'); ?></option>
    <option value="first_half" <?php selected($selected, 'first_half'); ?>><?php _e('Blur only the first half', 'protect-my-infos'); ?></option>
    <option value="second_half" <?php selected($selected, 'second_half'); ?>><?php _e('Blur only the second half', 'protect-my-infos'); ?></option>
    </select>
    <?php
}

?>
