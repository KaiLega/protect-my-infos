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
            src="<?php echo esc_url(add_query_arg(array(
                'image' => 'logo',
                'nonce' => wp_create_nonce('yw_protect_my_infos_image_nonce')
            ), home_url('/'))); ?>" 
            alt="<?php esc_attr_e('Protect My Infos Logo', 'yw-protect-my-infos'); ?>" 
            width="266"
        />
    </div>
</header>
