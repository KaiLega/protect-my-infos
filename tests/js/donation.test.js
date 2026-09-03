beforeEach(() => {
    jest.resetModules();
    document.head.innerHTML = '';
    document.body.innerHTML = `
        <button type="button" id="yw-load-paypal">Donate</button>
        <p id="yw-paypal-status"></p>
        <div id="yw-donate-button"></div>
    `;

    global.ywProtectMyInfosLang = {
        locale: 'it_IT',
        altText: 'Donate with PayPal button',
        titleText: 'PayPal',
        sdkUrl: 'https://www.paypalobjects.com/donate/sdk/donate-sdk.js',
        loadingText: 'Loading PayPal...',
        errorText: 'Unable to load PayPal.'
    };
});

test('loads the PayPal SDK only after explicit interaction', () => {
    require('../../assets/js/donation-button.js');
    document.dispatchEvent(new Event('DOMContentLoaded'));

    expect(document.querySelector('script[src*="paypalobjects.com"]')).toBeNull();

    document.getElementById('yw-load-paypal').click();

    expect(document.querySelector('script[src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js"]')).not.toBeNull();
});
