jQuery(document).ready(function ($) {

    // Function to decode and show the encoded information
    function decodeAndShow($element, encodedInfo, infoType, textColor, icon) {
        if (!encodedInfo) {
            console.warn('Missing encoded data for element:', $element);
            return;
        }

        try {
            var decodedInfo = atob(encodedInfo);
            var link;
            if (infoType === 'phone') {
                link = '<a href="tel:' + decodedInfo + '" style="color:' + textColor + ';">' + decodedInfo + '</a>';
            } else if (infoType === 'email') {
                link = '<a href="mailto:' + decodedInfo + '" style="color:' + textColor + ';">' + decodedInfo + '</a>';
            }
            $element.html(icon + link);
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
        var icon         = $iconElement.length > 0 ? $iconElement[0].outerHTML + ' ' : '';

        if (!revealed) {
            // Prevent default action for non-mouseover events
            if (e.type !== 'mouseover') {
                e.preventDefault();
                e.stopImmediatePropagation();
            }

            // Reveal the information after a short delay
            setTimeout(function () {
                decodeAndShow($container, encodedInfo, infoType, textColor, icon);
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
        var icon         = $iconElement.length > 0 ? $iconElement[0].outerHTML + ' ' : '';

        if (!encodedInfo) {
            console.warn('Missing encoded data for placeholder:', $this);
            return;
        }

        if (!revealed) {
            // Prevent default action for non-mouseover events
            e.preventDefault();
            e.stopImmediatePropagation();

            setTimeout(function () {
                decodeAndShow($this, encodedInfo, infoType, textColor, icon);
                $this.data('revealed', true);
            }, 0);

            return false;
        }
    });
});
