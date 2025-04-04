import {PaymentMethod, PlaceOrderButton, SuccessPage} from '@helpers/checkout-objects';
import {setupCheckout} from '@helpers/setup-checkout';
import {test} from '@playwright/test';

test.describe('cashondelivery payment test', () => {
  test('should allow me to go to the success page', async ({page, context}) => {
    await setupCheckout(page, context, {
      profile: 'netherlands',
      config: {
        'yireo_loki_checkout/general/theme': 'onestep',
        'payment/cashondelivery/active': '1',
      }
    });

    const paymentMethod = new PaymentMethod(page, 'cashondelivery');
    await paymentMethod.select();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
