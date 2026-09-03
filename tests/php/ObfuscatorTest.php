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

    public function test_settings_sanitizer_normalizes_checkboxes_and_colors() {
        $options = yw_protect_my_infos_sanitize_options(
            array(
                'yw_protect_phone_numbers' => '25',
                'yw_protect_emails' => '0',
                'show_icons' => '-1',
                'enable_obfuscation' => '',
                'text_color' => 'invalid',
                'icons_color' => '#123456',
            )
        );

        $this->assertSame(1, $options['yw_protect_phone_numbers']);
        $this->assertSame(0, $options['yw_protect_emails']);
        $this->assertSame(1, $options['show_icons']);
        $this->assertSame(0, $options['enable_obfuscation']);
        $this->assertSame('#000000', $options['text_color']);
        $this->assertSame('#123456', $options['icons_color']);
    }

    public function test_settings_sanitizer_rejects_nested_values_for_scalar_fields() {
        $options = yw_protect_my_infos_sanitize_options(
            array(
                'text_color' => array('#ffffff'),
                'icons_color' => array('#ffffff'),
                'yw-obfuscation_type' => array('blurred'),
                'blur_mode' => array('center'),
                'reveal_phone_text' => array('Reveal'),
                'reveal_email_text' => array('Reveal'),
            )
        );

        $this->assertSame('#000000', $options['text_color']);
        $this->assertSame('#000000', $options['icons_color']);
        $this->assertSame('placeholder', $options['yw-obfuscation_type']);
        $this->assertSame('full', $options['blur_mode']);
        $this->assertArrayNotHasKey('reveal_phone_text', $options);
        $this->assertArrayNotHasKey('reveal_email_text', $options);
    }
}
