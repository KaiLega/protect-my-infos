const locale = ywProtectMyInfosLang.locale || 'en_US';
const altText = ywProtectMyInfosLang.altText;
const titleText = ywProtectMyInfosLang.titleText;

document.addEventListener('DOMContentLoaded', function () {

    function renderDonationButton() {
        if (typeof PayPal !== 'undefined' && PayPal.Donation) {
            PayPal.Donation.Button({
                env: 'production',
                hosted_button_id: '87SXE2YJQAUWE',
                image: {
                    src: 'https://www.paypalobjects.com/' + locale + '/i/btn/btn_donateCC_LG.gif',
                    alt: altText,
                    title: titleText
                }
            }).render('#yw-donate-button');
        } else {
            console.error('PayPal SDK not available.');
        }
    }

    // Check if PayPal SDK is already loaded
    if (typeof PayPal !== 'undefined') {
        renderDonationButton();
    } else {
        window.addEventListener('load', function () {
            if (typeof PayPal !== 'undefined') {
                renderDonationButton();
            } else {
                console.error('PayPal SDK still not available after window load.');
            }
        });
    }
    
});
