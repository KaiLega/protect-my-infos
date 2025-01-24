<?php
/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Retrieve the plugin version
function yw_protect_my_infos_get_plugin_version() {
    if (!function_exists('get_plugin_data')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/protect-my-infos/protect-my-infos.php');
    return $plugin_data['Version'] ?? '1.0';
}

// Enqueue frontend scripts and styles
function yw_protect_my_infos_enqueue_scripts() {
    $plugin_version = yw_protect_my_infos_get_plugin_version();

    // Enqueue the frontend script
    wp_enqueue_script(
        'yw-protect-my-infos-script',
        plugin_dir_url(__FILE__) . '../js/protect-my-infos.js',
        array('jquery'),
        $plugin_version,
        true
    );

    // Enqueue the frontend CSS
    wp_enqueue_style(
        'yw-protect-my-infos-css',
        plugin_dir_url(__FILE__) . '../css/frontend-styles.css',
        array(),
        $plugin_version
    );

    // Localize script with plugin options
    $options = get_option('yw_protect_my_infos_options');
    wp_localize_script('yw-protect-my-infos-script', 'ywProtectMyInfos', array(
        'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
        'imageNonce' => wp_create_nonce('yw_protect_my_infos_image_nonce'),
        'protectPhoneNumbers' => isset($options['yw_protect_phone_numbers']) ? intval($options['yw_protect_phone_numbers']) : 0,
        'protectEmails' => isset($options['protect_emails']) ? intval($options['protect_emails']) : 0,
        'enableObfuscation' => isset($options['enable_obfuscation']) ? intval($options['enable_obfuscation']) : 0,
        'revealPhoneText' => esc_html__('- Click to reveal the phone number -', 'protect-my-infos'),
        'revealEmailText' => esc_html__('- Click to reveal the email address -', 'protect-my-infos'),
    ));
}
add_action('wp_enqueue_scripts', 'yw_protect_my_infos_enqueue_scripts');

// Enqueue admin scripts and styles
function yw_protect_my_infos_enqueue_admin_scripts($hook_suffix) {
    if ($hook_suffix === 'toplevel_page_yw-protect-my-infos') {
        $plugin_version = yw_protect_my_infos_get_plugin_version();

        // Enqueue the color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        // Enqueue the admin CSS
        wp_enqueue_style(
            'yw-protect-my-infos-admin-css',
            plugin_dir_url(__FILE__) . '../css/admin-styles.css',
            array(),
            $plugin_version
        );

        // Enqueue the admin JS
        wp_enqueue_script(
            'yw-protect-my-infos-admin-script',
            plugin_dir_url(__FILE__) . '../js/protect-my-infos-admin.js',
            array('jquery', 'wp-color-picker'),
            $plugin_version,
            true
        );

        // Localize the admin script
        wp_localize_script(
            'yw-protect-my-infos-admin-script',
            'ywProtectMyInfos',
            array(
                'nonce' => wp_create_nonce('yw_protect_my_infos_nonce_action'),
                'imageNonce' => wp_create_nonce('yw_protect_my_infos_image_nonce'),
                'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
            )
        );

        // Enqueue PayPal SDK and donation button script
        wp_enqueue_script(
            'paypal-sdk',
            'https://www.paypalobjects.com/donate/sdk/donate-sdk.js',
            array(),
            $plugin_version,
            true
        );
        wp_enqueue_script(
            'yw-protect-my-infos-donation-button',
            plugin_dir_url(dirname(__FILE__)) . 'js/donation-button.js',
            array('paypal-sdk'),
            $plugin_version,
            true
        );

        // Localize PayPal script
        wp_localize_script('yw-protect-my-infos-donation-button', 'ywProtectMyInfosLang', array(
            'altText' => esc_html__('Donate with PayPal button', 'protect-my-infos'),
            'titleText' => esc_html__('PayPal - The safer, easier way to pay online!', 'protect-my-infos'),
            'locale' => get_locale()
        ));
    }
}
add_action('admin_enqueue_scripts', 'yw_protect_my_infos_enqueue_admin_scripts');
