import {Field, PaymentMethod, PlaceOrderButton, ShippingMethod, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import {waitForLokiAjax} from './helpers/core-objects';

const persistenceConfig = merge(coreConfig, {
  profile: 'netherlands',
  config: {
    'loki_checkout/general/theme': 'onestep',
    'carriers/flatrate/active': 1,
    'payment/checkmo/active': 1,
  }
});

const fields = {
  'customer.email': 'persistence@example.com',
  'shipping.firstname': 'Jane',
  'shipping.lastname': 'Persistent',
  'shipping.company': 'Persistence Inc',
  'shipping.street0': 'Persistentlaan',
  'shipping.street1': '7',
  'shipping.city': 'Utrecht',
  'shipping.telephone': '0301234567',
};

test.describe('Persistence after reload', function () {
  test('should keep all entered values after reloading the checkout', async function ({page, context}) {
    await setupCheckout(page, context, persistenceConfig);

    for (const [fieldName, fieldValue] of Object.entries(fields)) {
      const field = new Field(page, fieldName);
      await field.fill(fieldValue);
      await field.expectValue(fieldValue);
    }

    await (new ShippingMethod(page, 'flatrate_flatrate')).select();
    await waitForLokiAjax(page, () => (new PaymentMethod(page, 'checkmo')).select());

    await page.reload();
    await expect(page).toHaveTitle(/Checkout/);

    for (const [fieldName, fieldValue] of Object.entries(fields)) {
      await expect(new Field(page, fieldName).getLocator()).toHaveValue(fieldValue);
    }

    await expect(new ShippingMethod(page, 'flatrate_flatrate').getLocator()).toBeChecked();
    await expect(new PaymentMethod(page, 'checkmo').getLocator()).toBeChecked();

    await (new PlaceOrderButton(page)).click();
    await new SuccessPage(page).expectToBeLoaded();
  });
});
