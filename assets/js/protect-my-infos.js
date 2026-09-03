jQuery(document).ready(function ($) {

    // Decode the UTF-8 value stored in the Base64 attribute.
    function decodeBase64Utf8(encodedInfo) {
        var binary = atob(encodedInfo);
        var bytes = new Uint8Array(binary.length);

        for (var i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }

        if (typeof TextDecoder !== 'undefined') {
            return new TextDecoder('utf-8', { fatal: true }).decode(bytes);
        }

        var escapedBytes = '';
        for (var j = 0; j < bytes.length; j++) {
            var hexByte = bytes[j].toString(16);
            escapedBytes += '%' + (hexByte.length === 1 ? '0' : '') + hexByte;
        }

        return decodeURIComponent(escapedBytes);
    }

    // Decode and render using DOM APIs so the protected value is never parsed as HTML.
    function decodeAndShow($element, encodedInfo, infoType, textColor, $iconElement, moveFocus) {
        if (!encodedInfo) {
            console.warn('Missing encoded data for element:', $element);
            return;
        }

        try {
            var decodedInfo = decodeBase64Utf8(encodedInfo);
            var linkPrefix;

            if (infoType === 'phone') {
                linkPrefix = 'tel:';
            } else if (infoType === 'email') {
                linkPrefix = 'mailto:';
            } else {
                throw new Error('Unsupported protected information type.');
            }

            var $link = $('<a>')
                .attr('href', linkPrefix + decodedInfo)
                .css('color', textColor)
                .text(decodedInfo);

            var $revealed = $('<span>')
                .addClass('yw-protect-info yw-protect-info-revealed')
                .attr({
                    'data-type': infoType,
                    'role': 'status',
                    'aria-live': 'polite'
                })
                .css('color', textColor);

            if ($iconElement && $iconElement.length) {
                $revealed.append($iconElement);
            }
            $revealed.append($link);
            $element.attr('aria-expanded', 'true').replaceWith($revealed);

            if (moveFocus) {
                $link.trigger('focus');
            }
        } catch (e) {
            console.error('Error decoding data:', e);
        }
    }

    // Preserve hover-to-reveal for blurred values.
    $(document).on('mouseover', '.yw-protect-info-button[data-obfuscated="true"] .yw-blurred-info', function () {
        var $container   = $(this).closest('.yw-protect-info');
        var encodedInfo  = $container.data('encoded');
        var infoType     = $container.data('type');
        var textColor    = $container.css('color');
        var $iconElement = $container.find('.dashicons').first().clone();

        decodeAndShow($container, encodedInfo, infoType, textColor, $iconElement, false);
    });

    // A native button provides click, Enter, and Space keyboard activation.
    $(document).on('click', '.yw-protect-info-button[data-obfuscated="true"]', function (e) {
        var $this = $(this);
        var encodedInfo  = $this.data('encoded');
        var infoType     = $this.data('type');
        var textColor    = $this.css('color');
        var $iconElement = $this.find('.dashicons').clone();

        if (!encodedInfo) {
            console.warn('Missing encoded data for placeholder:', $this);
            return;
        }

        e.preventDefault();
        decodeAndShow($this, encodedInfo, infoType, textColor, $iconElement, true);
    });
});
