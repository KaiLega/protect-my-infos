const $ = require('jquery');
const { TextDecoder } = require('util');

global.jQuery = $;
global.$ = $;
global.TextDecoder = TextDecoder;

function protectedButton(type, value) {
    const encoded = Buffer.from(value, 'utf8').toString('base64');
    return `<button type="button" class="yw-protect-info yw-protect-info-button" data-type="${type}" data-obfuscated="true" data-encoded="${encoded}" aria-expanded="false">Reveal</button>`;
}

beforeAll((done) => {
    require('../../assets/js/protect-my-infos.js');
    $(document).ready(function () {
        done();
    });
    document.dispatchEvent(new Event('DOMContentLoaded'));
});

test('reveals UTF-8 values without mojibake', () => {
    document.body.innerHTML = [
        protectedButton('email', 'müller@example.com'),
        protectedButton('email', '用户@example.com')
    ].join('');

    const buttons = document.querySelectorAll('.yw-protect-info-button');

    $(buttons[0]).trigger('click');
    $(buttons[1]).trigger('click');

    const links = document.querySelectorAll('.yw-protect-info-revealed a');
    expect(links[0].textContent).toBe('müller@example.com');
    expect(links[1].textContent).toBe('用户@example.com');
});

test('renders decoded values as text instead of executable HTML', () => {
    document.body.innerHTML = protectedButton('email', '<img src=x onerror=alert(1)>');

    const button = document.querySelector('.yw-protect-info-button');
    $(button).trigger('click');

    const revealed = document.querySelector('.yw-protect-info-revealed');
    expect(revealed.querySelector('img')).toBeNull();
    expect(revealed.querySelector('a').textContent).toBe('<img src=x onerror=alert(1)>');
});

test('moves focus to the revealed link after button activation', () => {
    document.body.innerHTML = protectedButton('phone', '+390123456789');

    const button = document.querySelector('.yw-protect-info-button');
    button.focus();
    $(button).trigger('click');

    const link = document.querySelector('.yw-protect-info-revealed a');
    expect(document.activeElement).toBe(link);
});
