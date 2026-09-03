<?php

use PHPUnit\Framework\TestCase;

final class ObfuscatorTest extends TestCase {
    private function options(array $overrides = array()) {
        return array_merge(
            array(
                'enable_obfuscation' => 1,
                'yw_protect_phone_numbers' => 1,
                'yw_protect_emails' => 1,
                'yw-obfuscation_type' => 'placeholder',
                'blur_mode' => 'full',
                'show_icons' => 1,
                'text_color' => '#000000',
                'icons_color' => '#000000',
            ),
            $overrides
        );
    }

    public function test_rejects_unknown_information_type() {
        $this->assertSame('', YW_Protect_My_Infos_Obfuscator::generate('url', 'example.com', $this->options()));
    }

    public function test_invalid_modes_fall_back_to_safe_allowlist_defaults() {
        $output = YW_Protect_My_Infos_Obfuscator::generate(
            'email',
            'user@example.com',
            $this->options(array('yw-obfuscation_type' => 'invalid', 'blur_mode' => 'invalid'))
        );

        $this->assertStringStartsWith('<button', $output);
        $this->assertStringContainsString('yw-protect-info-button', $output);
        $this->assertStringContainsString('aria-expanded="false"', $output);
        $this->assertStringContainsString('- Click to reveal the email address -', $output);

        $blurred = YW_Protect_My_Infos_Obfuscator::apply_blur_mode('abcd', 'invalid');
        $this->assertStringContainsString('>****</span>', $blurred);
    }

    public function test_short_and_unicode_values_are_supported() {
        $this->assertStringContainsString('>*</span>', YW_Protect_My_Infos_Obfuscator::apply_blur_mode('0', 'center'));

        $output = YW_Protect_My_Infos_Obfuscator::apply_blur_mode('müller@example.com', 'center');
        $this->assertStringStartsWith('m', $output);
        $this->assertStringEndsWith('m', $output);
    }

    public function test_partial_blur_escapes_visible_content() {
        $output = YW_Protect_My_Infos_Obfuscator::apply_blur_mode('<img src=x onerror=alert(1)>', 'second_half');

        $this->assertStringNotContainsString('<img', $output);
        $this->assertStringContainsString('&lt;img', $output);
    }

    public function test_settings_sanitizer_enforces_mode_allowlists() {
        $options = yw_protect_my_infos_sanitize_options(
            array(
                'yw-obfuscation_type' => 'unknown',
                'blur_mode' => 'unknown',
            )
        );

        $this->assertSame('placeholder', $options['yw-obfuscation_type']);
        $this->assertSame('full', $options['blur_mode']);
    }
}
