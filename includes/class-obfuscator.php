<?php
/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Protect_My_Infos_Obfuscator
 * Handles the logic for obfuscating sensitive information.
 */
class Protect_My_Infos_Obfuscator {

    /**
     * Generate the protected output for the given data.
     *
     * @param string $type The type of data ('phone' or 'email').
     * @param string $value The actual value to protect.
     * @param array $options The plugin options for customization.
     * @return string The generated HTML output.
     */
    public static function generate($type, $value, $options) {
        if (empty($value)) {
            return '';
        }

        // Encode the value to protect it from bots
        $encodedValue = base64_encode($value);
        $enableObfuscation = isset($options['enable_obfuscation']) ? intval($options['enable_obfuscation']) : 0;
        $obfuscationType = isset($options['obfuscation_type']) ? sanitize_text_field($options['obfuscation_type']) : 'placeholder';
        $blurMode = isset($options['blur_mode']) ? sanitize_text_field($options['blur_mode']) : 'full';
        $showIcons = isset($options['show_icons']) ? intval($options['show_icons']) : 0;
        $textColor = isset($options['text_color']) ? esc_attr($options['text_color']) : '#000000';
        $iconsColor = isset($options['icons_color']) ? esc_attr($options['icons_color']) : '#000000';

        $icon = '';
        if ($showIcons) {
            $icon = ($type === 'phone')
                ? '<span class="dashicons dashicons-phone" style="color: ' . $iconsColor . ';"></span> '
                : '<span class="dashicons dashicons-email" style="color: ' . $iconsColor . ';"></span> ';
        }

        $output = '';

        if ($enableObfuscation && $obfuscationType === 'blurred') {
            $blurredValue = self::apply_blur_mode($value, $blurMode);
            $output = '<span class="protect-info" data-type="' . esc_attr($type) . '" data-obfuscated="true" data-encoded="' . esc_attr($encodedValue) . '" style="color:' . esc_attr($textColor) . ';">' . $icon . $blurredValue . '</span>';
        } elseif ($enableObfuscation && $obfuscationType === 'placeholder') {
            $customText = ($type === 'phone')
                ? (isset($options['reveal_phone_text']) && !empty($options['reveal_phone_text']) ? esc_html($options['reveal_phone_text']) : esc_html__('- Click to reveal the phone number -', 'protect-my-infos'))
                : (isset($options['reveal_email_text']) && !empty($options['reveal_email_text']) ? esc_html($options['reveal_email_text']) : esc_html__('- Click to reveal the email address -', 'protect-my-infos'));

            $output = '<span class="protect-info" data-type="' . esc_attr($type) . '" data-encoded="' . esc_attr($encodedValue) . '" data-obfuscated="true" style="font-style: italic; color:' . esc_attr($textColor) . '; cursor: pointer;">' . $icon . $customText . '</span>';
        } else {
            $output = '<span class="protect-info" data-type="' . esc_attr($type) . '" data-encoded="' . esc_attr($encodedValue) . '" style="font-style: italic; color:' . esc_attr($textColor) . '; cursor: text;">' . $icon . '<span class="hidden-info"></span></span>';
        }

        return $output;
    }

    /**
     * Apply blur mode to the value.
     *
     * @param string $value The value to blur.
     * @param string $blurMode The mode of blur ('full', 'center', 'first_half', 'second_half').
     * @return string The blurred value.
     */
    public static function apply_blur_mode($value, $blurMode) {
        $length = strlen($value);
        $placeholder = str_repeat('*', $length); // Maintain the original length of the data
        $output = '';

        switch ($blurMode) {
            case 'center':
                $first = substr($value, 0, 1);
                $last = substr($value, -1);
                $middle = str_repeat('*', $length - 2); // Maintain the middle blur length
                $output = $first . '<span class="blurred-info" style="filter: blur(3px); cursor: pointer;" title="">' . $middle . '</span>' . $last;
                break;
            case 'first_half':
                $half = floor($length / 2);
                $output = '<span class="blurred-info" style="filter: blur(3px); cursor: pointer;" title="">' . str_repeat('*', $half) . '</span>' . substr($value, $half);
                break;
            case 'second_half':
                $half = floor($length / 2);
                $output = substr($value, 0, $half) . '<span class="blurred-info" style="filter: blur(3px); cursor: pointer;" title="">' . str_repeat('*', $length - $half) . '</span>';
                break;
            case 'full':
            default:
                $output = '<span class="blurred-info" style="filter: blur(3px); cursor: pointer;" title="">' . $placeholder . '</span>';
                break;
        }

        return $output;
    }
}
