<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the obfuscator class
require_once plugin_dir_path(__FILE__) . 'class-yw-protect-my-infos-obfuscator.php';

/**
 * Register the shortcode [yw_protect_my_infos].
 */
add_shortcode('yw_protect_my_infos', 'yw_protect_my_infos_shortcode');

/**
 * Shortcode handler for [yw_protect_my_infos].
 *
 * @param array $atts Shortcode attributes.
 * @return string Generated HTML output.
 */

function yw_protect_my_infos_shortcode($atts) {
    // Set up default attributes
    $atts = shortcode_atts(
        array(
            'type' => 'phone', // Default type is phone
            'value' => '', // The value to protect (e.g., phone number or email)
        ),
        $atts,
        'yw_protect_my_infos'
    );

    // If no value is provided, return an empty string
    if (empty($atts['value'])) {
        return esc_html__('No value provided.', 'protect-my-infos');
    }

    // Get plugin options from the database
    $options = get_option('yw_protect_my_infos_options', array());

    // Check if options are empty
    if (empty($options)) {
        return esc_html__('Settings not configured.', 'protect-my-infos');
    }

    // Use the obfuscator class to generate the protected output
    return YW_Protect_My_Infos_Obfuscator::generate($atts['type'], $atts['value'], $options);
}