/**
 * Copyright (c) 2024–present Yuga Web
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
        var $sel = $('#yw-obfuscation_type');
        var obfuscationType = $sel.length ? $sel.val() : '';
    
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

    // Initial call to set the correct visibility on page load
    if ($('#yw-obfuscation_type').length) {
        toggleRevealOptions();
        $('#yw-obfuscation_type').on('change', toggleRevealOptions);
    }
    
    // Handle form submission via AJAX
    $('#yw-protect-my-infos-settings-form').on('submit', function (e) {
        e.preventDefault();
    
        var $form = $(this);
        var formData = $form.serialize();
        var statusMessage = $('#yw-save-status');
        var $submit = $form.find('button[type="submit"], input[type="submit"]');

        // Disable the submit button and show a loading state
        $submit.prop('disabled', true).addClass('is-busy');

        // fallback ajaxUrl 
        var ajaxUrl = (window.ywProtectMyInfos && ywProtectMyInfos.ajaxUrl) ? ywProtectMyInfos.ajaxUrl
                    : (typeof ajaxurl !== 'undefined' ? ajaxurl : '');

        // Check if ajaxUrl is available
        if (!ajaxUrl) {
            statusMessage.text('AJAX endpoint not available.').css('color', 'red').show();
            $submit.prop('disabled', false).removeClass('is-busy');
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                action: 'yw_protect_my_infos_save_settings',
                security: (window.ywProtectMyInfos && ywProtectMyInfos.nonce) ? ywProtectMyInfos.nonce : '',
                options: formData
            },
            success: function (response) {
                if (response && response.success) {
                    statusMessage.text(response.data).css('color', 'green').show();
                    setTimeout(function () {
                        statusMessage.fadeOut('slow');
                    }, 3000);
                } else {
                    statusMessage.text((response && response.data) || 'Error saving settings.').css('color', 'red').show();
                }
            },
            error: function () {
                statusMessage.text('AJAX request failed. Please try again.').css('color', 'red').show();
            },
            complete: function () {
                $submit.prop('disabled', false).removeClass('is-busy');
            }
        });
    });

    // Hide the status message initially
    $('#yw-save-status').hide();
});
