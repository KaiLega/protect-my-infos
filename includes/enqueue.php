<?php
/**
 * Copyright (c) 2024–present Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Retrieve the plugin version
function yw_protect_my_infos_get_plugin_version() {
    if (defined('YW_PROTECT_MY_INFOS_VERSION')) {
        return YW_PROTECT_MY_INFOS_VERSION;
    }

    if (!function_exists('get_plugin_data')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $plugin_file = defined('YW_PLUGIN_FILE') ? YW_PLUGIN_FILE : dirname(__DIR__) . '/protect-my-infos.php';
    $plugin_data = get_plugin_data($plugin_file, false, false);

    return !empty($plugin_data['Version']) ? $plugin_data['Version'] : '1.0';
}

/**
 * Register frontend assets without loading them globally.
 */
function yw_protect_my_infos_register_frontend_assets() {
    $plugin_version = yw_protect_my_infos_get_plugin_version();

    wp_register_script(
        'yw-protect-my-infos-script',
        plugins_url('assets/js/protect-my-infos.js', YW_PLUGIN_FILE),
        array('jquery'),
        $plugin_version,
        true
    );

    wp_register_style(
        'yw-protect-my-infos-css',
        plugins_url('assets/css/frontend-styles.css', YW_PLUGIN_FILE),
        array(),
        $plugin_version
    );
}

/**
 * Enqueue frontend assets when protected content is rendered.
 */
function yw_protect_my_infos_enqueue_frontend_assets() {
    if (
        !wp_script_is('yw-protect-my-infos-script', 'registered')
        || !wp_style_is('yw-protect-my-infos-css', 'registered')
    ) {
        yw_protect_my_infos_register_frontend_assets();
    }

    wp_enqueue_script('yw-protect-my-infos-script');
    wp_enqueue_style('yw-protect-my-infos-css');

    $options = get_option('yw_protect_my_infos_options', array());
    if (!empty($options['show_icons'])) {
        wp_enqueue_style('dashicons');
    }
}

/**
 * Enqueue before wp_head when the shortcode occurs in queried post content.
 */
function yw_protect_my_infos_maybe_enqueue_frontend_assets() {
    global $wp_query;

    if (empty($wp_query->posts) || !is_array($wp_query->posts)) {
        return;
    }

    foreach ($wp_query->posts as $post) {
        if (isset($post->post_content) && has_shortcode($post->post_content, 'protect_my_infos')) {
            yw_protect_my_infos_enqueue_frontend_assets();
            return;
        }
    }
}
add_action('wp_enqueue_scripts', 'yw_protect_my_infos_maybe_enqueue_frontend_assets', 10);

/**
 * Print styles that were enqueued by a shortcode rendered after wp_head.
 *
 * This covers widgets, page builders, and template-level do_shortcode() calls
 * on WordPress versions that do not handle late-enqueued styles automatically.
 */
function yw_protect_my_infos_print_late_frontend_styles() {
    $handles = array();

    if (
        wp_style_is('yw-protect-my-infos-css', 'enqueued')
        && !wp_style_is('yw-protect-my-infos-css', 'done')
    ) {
        $handles[] = 'yw-protect-my-infos-css';
    }

    if (wp_style_is('dashicons', 'enqueued') && !wp_style_is('dashicons', 'done')) {
        $handles[] = 'dashicons';
    }

    if (!empty($handles)) {
        wp_print_styles($handles);
    }
}
add_action('wp_footer', 'yw_protect_my_infos_print_late_frontend_styles', 1);

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
            plugins_url('assets/css/admin-styles.css', YW_PLUGIN_FILE),
            array(),
            $plugin_version
        );

        // Enqueue the admin JS
        wp_enqueue_script(
            'yw-protect-my-infos-admin-script',
            plugins_url('assets/js/protect-my-infos-admin.js', YW_PLUGIN_FILE),
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
                'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
            )
        );

        // The local loader requests the PayPal SDK only after explicit interaction.
        wp_enqueue_script(
            'yw-protect-my-infos-donation-button',
            plugins_url('assets/js/donation-button.js', YW_PLUGIN_FILE),
            array(),
            $plugin_version,
            true
        );

        // Localize PayPal script
        wp_localize_script('yw-protect-my-infos-donation-button', 'ywProtectMyInfosLang', array(
            'altText' => esc_html__('Donate with PayPal button', 'protect-my-infos'),
            'titleText' => esc_html__('PayPal - The safer, easier way to pay online!', 'protect-my-infos'),
            'locale' => get_locale(),
            'sdkUrl' => 'https://www.paypalobjects.com/donate/sdk/donate-sdk.js',
            'loadingText' => esc_html__('Loading PayPal...', 'protect-my-infos'),
            'errorText' => esc_html__('Unable to load PayPal. Please try again.', 'protect-my-infos'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'yw_protect_my_infos_enqueue_admin_scripts');
