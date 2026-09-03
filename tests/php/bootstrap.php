<?php

define('ABSPATH', dirname(__DIR__, 2) . '/');

function sanitize_text_field($value) {
    return trim(strip_tags((string) $value));
}

function sanitize_key($value) {
    return preg_replace('/[^a-z0-9_\-]/', '', strtolower((string) $value));
}

function sanitize_hex_color($value) {
    return preg_match('/^#[a-f0-9]{6}$/i', (string) $value) ? $value : null;
}

function esc_attr($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function esc_html($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function esc_attr__($value, $domain) {
    return esc_attr($value);
}

function esc_html__($value, $domain) {
    return esc_html($value);
}

function add_action() {
    // WordPress hook registration is not required for these focused unit tests.
}

require_once dirname(__DIR__, 2) . '/includes/class-yw-protect-my-infos-obfuscator.php';
require_once dirname(__DIR__, 2) . '/includes/settings/settings-sections.php';
