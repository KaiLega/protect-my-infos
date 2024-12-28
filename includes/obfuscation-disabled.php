<?php

/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Encode the value as Base64 for clear display without need for clicks
$encodedValue = base64_encode($value);

// Show the data immediately when obfuscation is disabled
$output = '<span class="protect-info" data-type="' . esc_attr($atts['type']) . '" data-encoded="' . esc_attr($encodedValue) . '" data-obfuscated="false" style="font-style: italic; color: ' . esc_attr($revealTextColor) . '; cursor: text;">' . $icon . '<span class="decoded-info"></span></span>';
?>
