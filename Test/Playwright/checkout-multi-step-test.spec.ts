import {Field, ShippingMethod, PaymentMethod, PlaceOrderButton, SuccessPage, ShippingForwardButton} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

test.describe('complete multi-step checkout', function () {
  const currentConfig = merge(coreConfig, {
    config: {
      'loki_checkout/general/theme': 'default',
    }
  });

  test('should allow to me checkout completely', async function ({page,context}) {
    await setupCheckout(page, context, currentConfig);

    const fields = {
      'customer.email': 'jane@example.com',
      'shipping.firstname': 'Jane',
      'shipping.lastname': 'Doe',
      'shipping.company': 'Yireo',
      'shipping.city': 'Baarn',
      'shipping.street0': 'Amalialaan',
      'shipping.street1': '126',
      'shipping.telephone': '0123456789',
    };

    for (const [fieldName, fieldValue] of Object.entries(fields)) {
      const field = new Field(page, fieldName);
      await field.fill(fieldValue);
      await field.expectValue(fieldValue);
    }

    await (new ShippingMethod(page, 'flatrate_flatrate')).select();
    await (new ShippingForwardButton(page)).click();

    await (new PaymentMethod(page, 'checkmo')).select();


    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
