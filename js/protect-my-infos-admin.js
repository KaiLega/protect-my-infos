/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

jQuery(document).ready(function ($) {
    
    // Initialize color picker with a change event to update the general text color
    $('.yw-color-field').wpColorPicker({
        change: function (event, ui) {
            var color = ui.color.toString();
            $('.yw-protect-info, .yw-protect-info a').css('color', color);
        }
    });

    // Retrieve the last active section from local storage, default to '#yw-general-settings'
    var activeSection = localStorage.getItem('ywActiveSection') || '#yw-general-settings';

    // Handle navigation between settings sections
    $('.yw-sidebar-menu a').on('click', function (e) {
        e.preventDefault();
        var targetSection = $(this).attr('href');

        $('.yw-settings-section').removeClass('active');
        $(targetSection).addClass('active');

        $('.yw-sidebar-menu li').removeClass('active');
        $(this).closest('li').addClass('active');

        // Store the active section in local storage
        localStorage.setItem('ywActiveSection', targetSection);
    });

    // Apply the stored active section on page load
    $('.yw-settings-section').removeClass('active');
    $(activeSection).addClass('active');
    $('.yw-sidebar-menu li').removeClass('active');
    $('.yw-sidebar-menu a[href="' + activeSection + '"]').closest('li').addClass('active');

    // Toggle visibility of reveal options based on the selected obfuscation type
    function toggleRevealOptions() {
        var obfuscationType = $('#yw-obfuscation_type').val();
    
        if (obfuscationType === 'placeholder') {
            $('.yw-blur-mode-option').hide();
            $('.yw-reveal-option').show();
        } else if (obfuscationType === 'blurred') {
            $('.yw-reveal-option').hide();
            $('.yw-blur-mode-option').show();
        } else {
            $('.yw-reveal-option, .yw-blur-mode-option').hide();
        }
    }

    // Run the function on page load to set initial visibility
    $(document).ready(function () {
        toggleRevealOptions();
    
        $('#yw-obfuscation_type').on('change', toggleRevealOptions);
    });
    
    // Handle form submission via AJAX
    $('#yw-protect-my-infos-settings-form').on('submit', function (e) {
        e.preventDefault();
    
        var formData = $(this).serialize();
     
        var statusMessage = $('#yw-save-status');
    
        $.ajax({
            type: 'POST',
            url: ywProtectMyInfos.ajaxUrl,
            data: {
                action: 'yw_protect_my_infos_save_settings',
                security: ywProtectMyInfos.nonce,
                options: formData
            },
            success: function (response) {
                if (response.success) {
                    statusMessage.text(response.data).css('color', 'green').show();
                    setTimeout(function () {
                        statusMessage.fadeOut('slow');
                    }, 3000);
                } else {
                    statusMessage.text(response.data || 'Error saving settings.').css('color', 'red').show();
                }
            },
            error: function () {
                statusMessage.text('AJAX request failed. Please try again.').css('color', 'red').show();
            }
        });
    });

    // Hide the status message initially
    $('#yw-save-status').hide();
});
