const {PaymentMethod, PlaceOrderButton, SuccessPage} = require(process.cwd() + '/helpers/checkout-objects');
const {setupCheckout} = require(process.cwd() + '/helpers/setup-checkout');
const {test} = require(process.cwd() + '/node_modules/@playwright/test');

test.describe('Check Money Order payment test', () => {
  test('should allow me to go to the success page', async ({page, context}) => {
    await setupCheckout(page, context, {
      profile: 'netherlands',
      config: {
        'yireo_loki_checkout/general/theme': 'onestep'
      }
    });

    const paymentMethod = new PaymentMethod(page, 'checkmo');
    await paymentMethod.select();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
