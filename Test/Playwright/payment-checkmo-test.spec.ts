const {PaymentMethod, PlaceOrderButton, SuccessPage} = require(process.cwd() + '/helpers/checkout-objects');
const {saveCheckoutConfig} = require(process.cwd() + '/helpers/save-checkout-config');
const {test, expect} = require(process.cwd() + '/fixtures/checkout-page');

test.describe('Check Money Order payment test', () => {
    test('should allow me to go to the success page', async ({page, context}) => {
        await saveCheckoutConfig(context, {
            profile: 'dutch',
            config: {
                'yireo_loki_checkout/general/theme': 'onestep'
            }
        });

        await page.goto('/checkout');

        const paymentMethod = new PaymentMethod(page, 'checkmo');
        await paymentMethod.select();

        const placeOrderButton = new PlaceOrderButton(page);
        await placeOrderButton.click();

        await new SuccessPage(page).expectToBeLoaded();
    });
});
