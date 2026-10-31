import {PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

const themes = [
    {name: 'Luma', theme: 'Magento/luma'},
    {name: 'Hyvä', theme: 'Hyva/default-csp'},
];

const captchaConfig = {
    'customer/captcha/enable': 1,
    'customer/captcha/forms': 'payment_processing_request',
    'customer/captcha/mode': 'after_fail',
    'customer/captcha/failed_attempts_login': 3,
    'customer/captcha/failed_attempts_ip': 100000,
};

test.describe('payment rate limit', () => {
    test.afterEach(async ({page}) => {
        const response = await page.request.post(
            '/loki_functional_tests/index/configure?token=' + encodeURIComponent(process.env.TEST_TOKEN ?? ''),
            {form: {config: JSON.stringify({config: {'customer/captcha/enable': 0}})}}
        );
        expect(response.status()).toBe(200);
    });

    for (const theme of themes) {
        test(`switching payment methods does not block placing the order (${theme.name})`, async ({page, context}) => {
            const email = `rate-limit-${theme.theme.replace(/\W/g, '-')}-${Date.now()}@loki-checkout.com`.toLowerCase();

            await setupCheckout(page, context, merge(coreConfig, {
                theme: theme.theme,
                customer: {email, login: true},
                config: {
                    ...captchaConfig,
                    'payment/checkmo/active': 1,
                    'payment/banktransfer/active': 1,
                },
            }));

            const checkmo = new PaymentMethod(page, 'checkmo');
            const banktransfer = new PaymentMethod(page, 'banktransfer');
            for (let i = 0; i < 2; i++) {
                await banktransfer.select();
                await checkmo.select();
            }

            await new PlaceOrderButton(page).click();
            await new SuccessPage(page).expectToBeLoaded();
        });
    }
});
