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

<header class="yw-protect-my-infos-settings-header">
    <div class="yw-protect-my-infos-settings__wrapper">
        
        <!-- Display the logo with a secured and escaped URL -->
        <img 
            src="<?php echo esc_url(yw_protect_my_infos_get_image_url('logo')); ?>" 
            alt="<?php esc_attr_e('Protect My Infos Logo', 'protect-my-infos'); ?>" 
            width="266"
        />

    </div>
</header>
