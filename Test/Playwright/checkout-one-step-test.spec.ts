import {Field, ShippingMethod, PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test} from '@loki/test';
import coreConfig from '@loki/config';

test.describe('complete one-step checkout', function () {
  test('should allow to me checkout completely', async function ({page,context}) {
    await setupCheckout(page, context, {
      ...coreConfig,
      config: {
        ...coreConfig.config,
      }
    });

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
    await (new PaymentMethod(page, 'checkmo')).select();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
