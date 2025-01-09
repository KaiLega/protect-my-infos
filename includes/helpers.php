<?php
/**
 * Helper functions for Protect My Infos plugin.
 * Copyright (c) 2024 Yuga Web
 */

// Ensure this file is only accessed through WordPress
if (!defined('ABSPATH')) {
    exit;
}

// Display an admin notice
function protect_my_infos_admin_notice() {
    echo '<div class="notice notice-info is-dismissible">
        <p>' . esc_html__('Thank you for using Protect My Infos! ', 'protect-my-infos') . 
        '<a href="https://yugaweb.com" target="_blank">' . esc_html__('Visit our website for updates.', 'protect-my-infos') . '</a></p>
    </div>';
}
add_action('admin_notices', 'protect_my_infos_admin_notice');

// Serve images from a centralized location
function protect_my_infos_serve_image() {
    if (!isset($_GET['image']) || empty($_GET['image'])) {
        return; // Exit if no valid "image" parameter
    }

    // Verify the nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['nonce'])), 'protect_my_infos_image_nonce')) {
        wp_die(esc_html__('Invalid nonce.', 'protect-my-infos'), 403);
    }

    $image_key = sanitize_text_field(wp_unslash($_GET['image']));

    // Define the root URL for images
    $image_root_url = 'https://www.yugaweb.com/file/protect-my-infos/';

    // Define allowed images with relative paths
    $allowed_images = [
        'banner' => 'banner.png',
        'logo' => 'protect-my-infos_logo.png',
        'qr-code' => 'qr-code.svg',
    ];

    // Check if the requested image key exists
    if (!array_key_exists($image_key, $allowed_images)) {
        wp_die(esc_html__('Invalid image parameter.', 'protect-my-infos'), 403);
    }

    // Generate the full URL
    $image_url = $image_root_url . $allowed_images[$image_key];

    // Redirect to the external URL
    wp_redirect($image_url);
    exit;
}
add_action('template_redirect', 'protect_my_infos_serve_image');


function protect_my_infos_get_image_url($image_key) {
    // Define the root URL for images
    $image_root_url = 'https://www.yugaweb.com/file/protect-my-infos/';

    // Allowed images
    $allowed_images = [
        'banner' => 'banner.png',
        'logo' => 'protect-my-infos_logo.png',
        'qr-code' => 'qr-code.svg',
    ];

    // Check if the key exists in the allowed list
    if (!array_key_exists($image_key, $allowed_images)) {
        return ''; // Return an empty string for invalid keys
    }

    // Generate the nonce
    $nonce = wp_create_nonce('protect_my_infos_image_nonce');

    // Return the full URL with nonce
    return add_query_arg(
        array(
            'image' => $image_key,
            'nonce' => $nonce,
        ),
        $image_root_url . $allowed_images[$image_key]
    );
}

