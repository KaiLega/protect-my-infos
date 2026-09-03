=== Protect My Infos ===
Contributors: yugaweb, kaichi
Tags: security, privacy, email obfuscation, anti-spam, phone number protection
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.4.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Protect sensitive information like emails and phone numbers from bots with advanced obfuscation techniques.

== Description ==
**Protect My Infos** is a WordPress plugin designed to help protect sensitive contact information, such as phone numbers and email addresses, from basic automated harvesting through configurable client-side obfuscation.

Emails and phone numbers are encoded and hidden from bots, while visitors can interact with placeholders to reveal the information.

= Features =
- Obfuscate contact information with placeholders, blur effects, and Base64 encoding.
- Use the `[protect_my_infos]` shortcode for integration in posts or pages.
- Fully customizable settings for icons, colors, and reveal texts.
- Easy-to-use admin interface.

== Installation ==
1. Upload the plugin files to `/wp-content/plugins/protect-my-infos` or install it via the WordPress admin.
2. Activate the plugin in the 'Plugins' menu.
3. Configure the settings in `Settings > Protect My Infos`.

== Frequently Asked Questions ==
= How does the obfuscation work? =
The plugin uses Base64 encoding combined with JavaScript to discourage basic address-harvesting bots. Base64 is encoding, not encryption, and sophisticated crawlers may still recover the original value.

= Can I customize the icons and colors? =
Yes, the plugin allows you to change the icon style and colors for both text and icons directly from the settings page.

= Can I customize the reveal text? =
Yes, you can set custom reveal texts for both emails and phone numbers.

= Is the plugin compatible with all themes? =
It works with most WordPress themes. Theme-specific CSS can occasionally require small styling adjustments.

= Is the plugin compatible with caching plugins? =
Yes, the plugin works with most caching plugins. If you encounter any issues, try clearing your cache after activating Protect My Infos.

= Does this plugin slow down my website? =
The frontend assets are small and are loaded only on pages where the shortcode is detected or rendered. Actual performance depends on the site, theme, and other plugins.

= Can I protect other types of sensitive data? =
Currently, the plugin is designed to protect email addresses and phone numbers. Future updates may include support for additional types of data.

= How do I use the shortcode? =
You can use the shortcode `[protect_my_infos type="email" value="youremail@example.com"]` to protect an email, or `[protect_my_infos type="phone" value="+1234567890"]` to protect a phone number.

= Can I disable the obfuscation? =
Yes, you can disable the obfuscation in the plugin settings. However, this may expose your data to bots.

= Does the plugin work with all WordPress themes? =
Protect My Infos is compatible with most WordPress themes. If you experience layout issues, check your theme's custom styles or contact support.

= What happens if JavaScript is disabled in the browser? =
If JavaScript is disabled, the obfuscated data will not be revealed to users. Ensure your audience has JavaScript enabled for the best experience.

= Can I translate this plugin? =
Yes, Protect My Infos is translation-ready. You can use tools like Loco Translate or Poedit to translate the plugin into your desired language.

= Is there a Pro version of this plugin? =
Not yet, but we are working on additional premium features. Stay tuned for updates!

= Does the plugin comply with GDPR? =
The plugin stores its configuration in the WordPress database and does not transmit protected email addresses or phone numbers to Yuga Web. Clicking the PayPal donation button loads PayPal resources and is subject to PayPal's privacy terms. Compliance depends on how the site owner configures and uses the website.

== External Services ==

This plugin optionally integrates with PayPal Donate to facilitate donations. No PayPal resource is loaded until an administrator explicitly clicks the "Donate with PayPal" button on the plugin settings page.

- **Service Name**: PayPal Donate API
- **Purpose**: To provide a "Donate" button for collecting user donations securely via PayPal.
- **Data Sent**:
  - Network and device information normally included in web requests, such as IP address and user agent
  - WordPress locale and the hosted PayPal button identifier
  - Any payment information the administrator subsequently enters on PayPal
- **When**: The PayPal SDK is requested only after an administrator clicks the "Donate with PayPal" button.
- **Service Links**:
  - [PayPal Terms of Service](https://www.paypal.com/us/webapps/mpp/ua/legalhub-full)
  - [PayPal Privacy Policy](https://www.paypal.com/us/webapps/mpp/ua/privacy-full)

Protected phone numbers and email addresses are not sent to PayPal. Payment transactions are handled by PayPal under its own terms and privacy policy.

== Screenshots ==
1. **Admin Panel**: Configure the plugin settings from the WordPress admin.
2. **Frontend Protection**: Emails and phone numbers are protected on your site.

== Changelog ==
= 1.4.0 =
* Add allowlist validation and accessible keyboard reveal controls.
* Load frontend assets only when needed and enqueue Dashicons when enabled.
* Load PayPal resources only after explicit administrator interaction.
* Scope administrative notices and update messages to the plugin.
* Correct security and privacy claims in the documentation.
* Add automated PHP, JavaScript, WordPress compatibility, and Plugin Check workflows.
* Add consistent Git line-ending rules and remove duplicate generic Italian catalogs.

= 1.3.9 =
* Fix secure rendering of revealed email addresses and phone numbers.
* Fix fatal errors for one-character values in center blur mode.
* Add UTF-8 support for international email addresses and protected values.
* Declare compatibility with WordPress 7.1 and require PHP 7.4 or later.

= 1.3.8 =
* Declare compatibility with WordPress 6.9 and update documentation.

= 1.3.7 =
* Fix: two-tap reveal behavior on mobile for obfuscated emails/phones (first tap reveals, second tap opens link).

= 1.3.6 =
* Serve images locally from /assets (removed external CDN).
* Settings saved via AJAX with nonce; sanitization through the Settings API.
* Standardized option keys with fallbacks for prior versions.
* Refactored asset structure (css/js/img) and minor optimizations.

= 1.3.5 =
* Performance and localization updates

= 1.3.4 =
* Refactoring and security updates

= 1.3.3 =
* Performance and localization updates

= 1.3.2 =
* Security updates

= 1.3.1 =
* Added How to use section

= 1.3 =
* Added customizable settings for obfuscation type, reveal texts, and colors.

= 1.2 =
* Added obfuscation functionality.
* Added different types of obfuscation.
* Added icons.

= 1.1 =
* Added shortcode support.

= 1.0 =
* Initial release with email and phone number protection.


== Upgrade Notice ==
= 1.4.0 =
Improves validation, accessibility, privacy, and frontend performance, and adds automated compatibility checks. Clear all caches after updating.

= 1.3.9 =
Improves output security and UTF-8 support, fixes short-value blur errors, and adds WordPress 7.1 compatibility. Clear all caches after updating.

= 1.3.8 =
Declared compatibility with WordPress 6.9 and refreshed documentation. No action required; clear cache if you use a caching plugin.

= 1.3.7 =
Fix mobile reveal behavior: first tap reveals, second tap opens the link. No action required; clear cache if you use a caching plugin.
