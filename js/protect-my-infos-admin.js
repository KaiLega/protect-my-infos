/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

jQuery(document).ready(function ($) {
    // Initialize color picker with a change event to update the general text color
    $('.my-color-field').wpColorPicker({
        change: function (event, ui) {
            var color = ui.color.toString();
            $('.protect-info, .protect-info a').css('color', color);
        }
    });

    // Retrieve the last active section from local storage, default to '#general-settings'
    var activeSection = localStorage.getItem('activeSection') || '#general-settings';

    // Handle navigation between settings sections
    $('.sidebar-menu a').on('click', function (e) {
        e.preventDefault();
        var targetSection = $(this).attr('href');

        $('.settings-section').removeClass('active');
        $(targetSection).addClass('active');

        $('.sidebar-menu li').removeClass('active');
        $(this).closest('li').addClass('active');

        // Store the active section in local storage
        localStorage.setItem('activeSection', targetSection);
    });

    // Apply the stored active section on page load
    $('.settings-section').removeClass('active');
    $(activeSection).addClass('active');
    $('.sidebar-menu li').removeClass('active');
    $('.sidebar-menu a[href="' + activeSection + '"]').closest('li').addClass('active');

    // Toggle visibility of reveal options based on the selected obfuscation type
    function toggleRevealOptions() {
        var obfuscationType = $('#obfuscation_type').val();

        if (obfuscationType === 'placeholder') {
            $('.blur-mode-option').hide();
            $('.reveal-option').show();
        } else if (obfuscationType === 'blurred') {
            $('.reveal-option').hide();
            $('.blur-mode-option').show();
        } else {
            $('.reveal-option, .blur-mode-option').hide();
        }
    }

    // Run the function on page load to set initial visibility
    toggleRevealOptions();

    // Listen for changes in the obfuscation type dropdown
    $('#obfuscation_type').on('change', toggleRevealOptions);

    // Handle form submission via AJAX
    $('#protect-my-infos-settings-form').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var statusMessage = $('#save-status');

        $.ajax({
            type: 'POST',
            url: protectMyInfos.ajaxUrl,
            data: {
                action: 'protect_my_infos_save_settings',
                security: protectMyInfos.nonce,
                options: formData 
            },
            success: function (response) {
                if (response.success) {
                    statusMessage.text(response.data).css('color', 'green').show();
                    setTimeout(function () {
                        statusMessage.fadeOut('slow');
                    }, 3000);
                } else {
                    statusMessage.text(response.data || 'Errore durante il salvataggio delle impostazioni.').css('color', 'red').show();
                }
            },
            error: function () {
                statusMessage.text('Errore durante la richiesta AJAX. Riprova.').css('color', 'red').show();
            }
        });
    });

    // Hide the status message initially
    $('#save-status').hide();
});
