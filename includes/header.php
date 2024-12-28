
<?php
/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

// Ensure this file is only accessed through WordPress
if (!defined('ABSPATH')) {
    exit;
}

// HTML Header section with proper escaping and security
?>

<header class="gform-settings-header">
<div class="gform-settings__wrapper">
<!-- Display the logo with a secured and escaped URL -->
<img src="<?php echo esc_url(plugins_url('images/pmi_logo.png', dirname(__FILE__))); ?>" alt="<?php esc_attr_e('Protect My Infos', 'protect-my-infos'); ?>" width="266">
<div class="gform-settings-header_buttons">
<!-- Additional header buttons can be added here -->
</div>
</div>
</header>
