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
    $('.yw-protect-info').each(function () {
        var $this = $(this);
        var isObfuscated = $this.data('obfuscated') === true;
        var encodedInfo = $this.data('encoded');
        var infoType = $this.data('type');
        var textColor = $this.css('color');
        var $iconElement = $this.find('.dashicons').clone();
        var icon = $iconElement.length > 0 ? $iconElement[0].outerHTML + ' ' : '';

        if (isObfuscated && $this.find('.yw-blurred-info').length > 0) {
            $this.on('mouseover', '.yw-blurred-info', function () {
                console.log('Mouseover on blurred-info detected:', $this);
                $(this).css('filter', 'none'); 
                decodeAndShow($this, encodedInfo, infoType, textColor, icon);
            });
        }
    });

    // Function to handle click events
    $('.yw-protect-info[data-obfuscated="true"]').not('.yw-blurred-info').on('click', function () {
        var $this = $(this);
        var encodedInfo = $this.data('encoded');
        var infoType = $this.data('type');
        var textColor = $this.css('color');
        var $iconElement = $this.find('.dashicons').clone();
        var icon = $iconElement.length > 0 ? $iconElement[0].outerHTML + ' ' : '';

        if (!encodedInfo) {
            console.warn('Missing encoded data for placeholder:', $this);
            return;
        }

        decodeAndShow($this, encodedInfo, infoType, textColor, icon);
    });
});
