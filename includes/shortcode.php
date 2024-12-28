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
require_once plugin_dir_path(__FILE__) . 'class-obfuscator.php';

/**
 * Register the shortcode [protect_my_infos].
 */
add_shortcode('protect_my_infos', 'protect_my_infos_shortcode');

/**
 * Shortcode handler for [protect_my_infos].
 *
 * @param array $atts Shortcode attributes.
 * @return string Generated HTML output.
 */
function protect_my_infos_shortcode($atts) {
    // Set up default attributes
    $atts = shortcode_atts(
        array(
            'type' => 'phone', // Default type is phone
            'value' => '', // The value to protect (e.g., phone number or email)
        ),
        $atts,
        'protect_my_infos'
    );

    // If no value is provided, return an empty string
    if (empty($atts['value'])) {
        return '';
    }

    // Get plugin options from the database
    $options = get_option('protect_my_infos_options');

    // Use the obfuscator class to generate the protected output
    return Protect_My_Infos_Obfuscator::generate($atts['type'], $atts['value'], $options);
}
