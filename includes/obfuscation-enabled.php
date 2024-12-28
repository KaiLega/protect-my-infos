<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Encode the value as Base64 for obfuscation
$encodedValue = base64_encode($value);

// Determine the custom text or use the default one based on the type (phone/email)
$customText = $atts['type'] === 'phone'
? (isset($options['reveal_phone_text']) && !empty($options['reveal_phone_text'])
   ? esc_html($options['reveal_phone_text'])
   : esc_html__('- Click to reveal the phone number -', 'protect-my-infos'))
: (isset($options['reveal_email_text']) && !empty($options['reveal_email_text'])
? esc_html($options['reveal_email_text'])
: esc_html__('- Click to reveal the email address -', 'protect-my-infos'));

// Generate the output with proper escaping and encoding
$output = '<span class="protect-info" data-type="' . esc_attr($atts['type']) . '" data-encoded="' . esc_attr($encodedValue) . '" data-obfuscated="true" style="font-style: italic; color: ' . esc_attr($revealTextColor) . '; cursor: pointer;">' . $icon . $customText . '</span>';
?>
