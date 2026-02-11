import {Field, PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki/config';

test.describe('Logged in customer test', function()  {
  test('should turn postcode into uppercase', async function({page, context})  {
    await setupCheckout(page, context, {
      ...coreConfig,
      customer: {
        ...coreConfig.customer,
        login: true
      }
    });

    const paymentMethod = new PaymentMethod(page, 'checkmo');
    await paymentMethod.select();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
