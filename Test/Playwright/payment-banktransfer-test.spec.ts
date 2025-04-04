const {PaymentMethod, PlaceOrderButton, SuccessPage} = require(process.cwd() + '/helpers/checkout-objects');
const {setupCheckout} = require(process.cwd() + '/helpers/setup-checkout');
const {test} = require(process.cwd() + '/node_modules/@playwright/test');

test.describe('banktransfer payment test', () => {
  test('should allow me to go to the success page', async ({page, context}) => {
    await setupCheckout(page, context, {
      profile: 'netherlands',
      config: {
        'yireo_loki_checkout/general/theme': 'onestep',
        'payment/banktransfer/active': '1',
      }
    });

    const paymentMethod = new PaymentMethod(page, 'banktransfer');
    await paymentMethod.select();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
