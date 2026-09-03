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
    function decodeAndShow($element, encodedInfo, infoType, textColor, $iconElement) {
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

            $element.empty();
            if ($iconElement && $iconElement.length) {
                $element.append($iconElement);
            }
            $element.append($link);
        } catch (e) {
            console.error('Error decoding data:', e);
        }
    }

    // Function to handle mouseover events
    $(document).on('mouseover click touchend', '.yw-protect-info[data-obfuscated="true"] .yw-blurred-info', function (e) {
        var $container   = $(this).closest('.yw-protect-info');
        var revealed     = $container.data('revealed') === true;
        var encodedInfo  = $container.data('encoded');
        var infoType     = $container.data('type');
        var textColor    = $container.css('color');
        var $iconElement = $container.find('.dashicons').first().clone();

        if (!revealed) {
            // Prevent default action for non-mouseover events
            if (e.type !== 'mouseover') {
                e.preventDefault();
                e.stopImmediatePropagation();
            }

            // Reveal the information after a short delay
            setTimeout(function () {
                decodeAndShow($container, encodedInfo, infoType, textColor, $iconElement);
                $container.data('revealed', true);
            }, 0);

            return false;
        }

    });

    // Function to handle click events
    // (First tap reveals, second tap follows the link)
    $(document).on('click touchend', '.yw-protect-info[data-obfuscated="true"]', function (e) {
        var $this = $(this);

        // If already revealed, do nothing (let the link be followed)
        if ($this.find('.yw-blurred-info').length) {
            return;
        }

        var revealed     = $this.data('revealed') === true;
        var encodedInfo  = $this.data('encoded');
        var infoType     = $this.data('type');
        var textColor    = $this.css('color');
        var $iconElement = $this.find('.dashicons').clone();

        if (!encodedInfo) {
            console.warn('Missing encoded data for placeholder:', $this);
            return;
        }

        if (!revealed) {
            // Prevent default action for non-mouseover events
            e.preventDefault();
            e.stopImmediatePropagation();

            setTimeout(function () {
                decodeAndShow($this, encodedInfo, infoType, textColor, $iconElement);
                $this.data('revealed', true);
            }, 0);

            return false;
        }
    });
});
