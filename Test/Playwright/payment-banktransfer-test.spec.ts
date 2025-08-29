import {PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test} from '@loki/test';
import coreConfig from '@loki/config';

test.describe('banktransfer payment test', () => {
  test('should allow me to go to the success page', async ({page, context}) => {
    await setupCheckout(page, context, {
      ...coreConfig,
      config: {
        ...coreConfig.config,
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
