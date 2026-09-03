document.addEventListener('DOMContentLoaded', function () {
    var loadButton = document.getElementById('yw-load-paypal');
    var status = document.getElementById('yw-paypal-status');

    if (!loadButton || typeof ywProtectMyInfosLang === 'undefined') {
        return;
    }

    function setError() {
        loadButton.disabled = false;
        loadButton.hidden = false;
        status.textContent = ywProtectMyInfosLang.errorText;
    }

    function renderDonationButton() {
        if (typeof PayPal === 'undefined' || !PayPal.Donation) {
            setError();
            return;
        }

        PayPal.Donation.Button({
            env: 'production',
            hosted_button_id: '87SXE2YJQAUWE',
            image: {
                src: 'https://www.paypalobjects.com/' + (ywProtectMyInfosLang.locale || 'en_US') + '/i/btn/btn_donateCC_LG.gif',
                alt: ywProtectMyInfosLang.altText,
                title: ywProtectMyInfosLang.titleText
            }
        }).render('#yw-donate-button');

        loadButton.hidden = true;
        status.textContent = '';
    }

    loadButton.addEventListener('click', function () {
        loadButton.disabled = true;
        status.textContent = ywProtectMyInfosLang.loadingText;

        if (typeof PayPal !== 'undefined' && PayPal.Donation) {
            renderDonationButton();
            return;
        }

        var sdk = document.createElement('script');
        sdk.src = ywProtectMyInfosLang.sdkUrl;
        sdk.async = true;
        sdk.onload = renderDonationButton;
        sdk.onerror = setError;
        document.head.appendChild(sdk);
    });
});
