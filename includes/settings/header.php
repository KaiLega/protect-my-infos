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

        <?php
        // Get the logo URL using the helper function
        $logo_url = yw_protect_my_infos_get_image_url('logo');
        if (!empty($logo_url)) : ?>
            <!-- Display the logo with a secured and escaped URL -->
            <img
                class="yw-protect-my-infos-logo"
                src="<?php echo esc_url($logo_url); ?>"
                alt="<?php echo esc_attr__('Protect My Infos Logo', 'protect-my-infos'); ?>"
                width="266"
                loading="lazy"
                decoding="async"
            />
        <?php endif; ?>

    </div>
</header>