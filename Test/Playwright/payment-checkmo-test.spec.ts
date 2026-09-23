import {PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test} from '@loki/test';
import coreConfig from '@loki-checkout/config';

test.describe('checkmo payment test', () => {
  test('should allow me to go to the success page', async ({page, context}) => {
    await setupCheckout(page, context, {
      ...coreConfig,
      config: {
        ...coreConfig.config,
        'payment/checkmo/active': '1',
      }
    });

    const paymentMethod = new PaymentMethod(page, 'checkmo');
    await paymentMethod.select();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
