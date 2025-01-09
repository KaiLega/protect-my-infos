<?php
/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Enqueue frontend scripts and styles
function protect_my_infos_enqueue_scripts() {
    // Enqueue the frontend script
    wp_enqueue_script(
        'protect-my-infos-script',
        plugin_dir_url(__FILE__) . '../js/protect-my-infos.js',
        array('jquery'),
        '1.0',
        true
    );
    
    $options = get_option('protect_my_infos_options');
    wp_localize_script('protect-my-infos-script', 'protectMyInfos', array(
        'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
        'imageNonce' => wp_create_nonce('protect_my_infos_image_nonce'), 
        'protectPhoneNumbers' => isset($options['protect_phone_numbers']) ? intval($options['protect_phone_numbers']) : 0,
        'protectEmails' => isset($options['protect_emails']) ? intval($options['protect_emails']) : 0,
        'enableObfuscation' => isset($options['enable_obfuscation']) ? intval($options['enable_obfuscation']) : 0,
        'revealPhoneText' => esc_html__('- Click to reveal the phone number -', 'protect-my-infos'),
        'revealEmailText' => esc_html__('- Click to reveal the email address -', 'protect-my-infos'),
    ));
}
add_action('wp_enqueue_scripts', 'protect_my_infos_enqueue_scripts');

// Enqueue admin scripts and styles
function protect_my_infos_enqueue_admin_scripts($hook_suffix) {
    // Only enqueue on the plugin's settings page
    if ($hook_suffix === 'toplevel_page_protect-my-infos') {
        // Enqueue the color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Enqueue the admin CSS
        wp_enqueue_style(
            'protect-my-infos-admin-css',
            plugin_dir_url(__FILE__) . '../css/admin-styles.css',
            array(),
            '1.0'
        );
        
        // Enqueue the admin JS
        wp_enqueue_script(
            'protect-my-infos-admin-script',
            plugin_dir_url(__FILE__) . '../js/protect-my-infos-admin.js',
            array('jquery', 'wp-color-picker'),
            '1.0',
            true
        );
        
        // Localize the admin script
        wp_localize_script(
            'protect-my-infos-admin-script',
            'protectMyInfos',
            array(
                'nonce' => wp_create_nonce('protect_my_infos_nonce_action'), 
                'imageNonce' => wp_create_nonce('protect_my_infos_image_nonce'),
                'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
            )
        );
    }
}
add_action('admin_enqueue_scripts', 'protect_my_infos_enqueue_admin_scripts');
