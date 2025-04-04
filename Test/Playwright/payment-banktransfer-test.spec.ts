import {PaymentMethod, PlaceOrderButton, SuccessPage} from '@helpers/checkout-objects';
import {setupCheckout} from '@helpers/setup-checkout';
import {test} from '@playwright/test';

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
