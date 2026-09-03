<!--
Copyright (c) 2024 Yuga Web
This file is part of the Protect My Infos plugin.
License: GPLv2 or later. See LICENSE file for details.
-->

# Protect My Infos

**Protect My Infos** is a WordPress plugin designed to help protect sensitive contact information, such as phone numbers and email addresses, from basic automated harvesting through configurable client-side obfuscation. It provides configurable reveal methods and an administration interface in the WordPress admin panel.

The plugin uses Base64 encoding and JavaScript. Determined crawlers may still recover values, so it should not be treated as an access-control or data-security mechanism.

## Features

- **Data Obfuscation**: Obfuscate emails and phone numbers using placeholders, blur effects, and Base64 encoding.
- **Shortcode Support**: Use the `[protect_my_infos]` shortcode to protect specific information within your posts or pages.
- **Customizable Settings**: Configure obfuscation behavior, custom reveal texts, and text color through the admin panel.
- **Icon Support**: Display an icon next to protected data.

## Requirements

- WordPress 5.0 or higher.
- PHP 7.4 or higher.

## Installation

1. **Upload the plugin** to the `/wp-content/plugins/protect-my-infos` directory or install it directly through the WordPress plugin screen.
2. **Activate the plugin** through the 'Plugins' screen in WordPress.
3. **Configure the settings** in `Settings > Protect My Infos` from the admin panel.

## Usage

### Shortcode

You can use the `[protect_my_infos]` shortcode to protect your sensitive data. Examples:

```html
[protect_my_infos type="email" value="youremail@example.com"]

[protect_my_infos type="phone" value="+1234567890"]
```

## Options
- Enable Obfuscation: Turn on or off the data obfuscation.
- Obfuscation Type: Choose between placeholder text or blurred letters/numbers for obfuscation.
- Reveal Phone Text: Custom text to reveal the phone number (if placeholder is selected).
- Reveal Email Text: Custom text to reveal the email address (if placeholder is selected).
- Show Icons: Display an icon next to obfuscated data.
- Text Color: Choose the text color for protected data.
- Icons Color: Choose the icon color for protected data.

### Example Configuration
You can configure the plugin settings from the WordPress admin panel by going to Settings > Protect My Infos. Here, you can select the obfuscation type, set custom reveal texts, and define the behavior of the plugin.

## Contributing
If you'd like to contribute to this plugin, you are welcome to do so! Feel free to submit pull requests or report issues through the [official GitHub repository](https://github.com/KaiLega/protect-my-infos).

## Development and tests

Runtime plugin files do not require a build step. Development checks are available with:

```bash
composer install
composer test

npm ci
npm run test:js
```

The GitHub Actions workflow also runs WordPress smoke tests across WordPress 6.9, 7.0, and 7.1 with PHP 7.4, 8.2, and 8.3, plus the official WordPress Plugin Check action.

## License

This plugin is distributed under the **GPL v2.0** or later license. You can view the full license text at [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html).


© 2024 Yuga Web. All rights reserved.
