/**
 * Copyright (c) 2024 Yuga Web
 * This file is part of the Protect My Infos plugin.
 * License: GPLv2 or later. See LICENSE file for details.
 */

jQuery(document).ready(function($) {
    // Handling "With blurred letters/numbers"
    $('.protect-info').each(function() {
        var $this = $(this);
        var isObfuscated = $this.data('obfuscated') === true;
        var encodedInfo = $this.data('encoded');
        var infoType = $this.data('type');
        var icon = '';
        var textColor = $this.css('color');
        
        // Get icon HTML if present
        if ($this.find('.dashicons').length > 0) {
            icon = $this.find('.dashicons')[0].outerHTML + ' ';
        }
        
        // Decode and display the obfuscated data
        function decodeAndShow() {
            if (!encodedInfo) {
                return; // Exit if encodedInfo is undefined or empty
            }
            
            try {
                var decodedInfo = atob(encodedInfo);
                var link;
                if (infoType === 'phone') {
                    link = '<a href="tel:' + decodedInfo + '" style="color:' + textColor + ';">' + decodedInfo + '</a>';
                } else {
                    link = '<a href="mailto:' + decodedInfo + '" style="color:' + textColor + ';">' + decodedInfo + '</a>';
                }
                $this.html(icon + link);
            } catch (e) {
                // Handle decoding error silently
            }
        }
        
        if (isObfuscated) {
            $this.find('.blurred-info').on('mouseover', function() {
                $(this).css('filter', 'none'); // Remove blur effect on hover
                decodeAndShow(); // Decode and display the data
            });
        } else {
            decodeAndShow(); // Directly decode and show if not obfuscated
        }
    });
    
    // Handling "With placeholder text"
    $('.protect-info[data-obfuscated="true"]').not('.blurred-mode').on('click', function() {
        var $this = $(this);
        var encodedInfo = $this.data('encoded');
        var infoType = $this.data('type');
        var textColor = $this.css('color');
        var icon = $this.find('.dashicons').length > 0 ? $this.find('.dashicons')[0].outerHTML + ' ' : '';
        
        if (!encodedInfo) {
            return; // Exit if encodedInfo is undefined or empty
        }
        
        try {
            var decodedInfo = atob(encodedInfo);
            var link;
            if (infoType === 'phone') {
                link = '<a href="tel:' + decodedInfo + '" style="color:' + textColor + ';">' + decodedInfo + '</a>';
            } else {
                link = '<a href="mailto:' + decodedInfo + '" style="color:' + textColor + ';">' + decodedInfo + '</a>';
            }
            $this.html(icon + link);
        } catch (e) {
            // Handle decoding error silently
        }
    });
});
