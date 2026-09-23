import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';

test.describe('Postcode uppercase test', function()  {
  test('should turn postcode into uppercase', async function({page, context})  {
    await setupCheckout(page, context, {
      ...coreConfig
    });

    const postcodeField = new Field(page, 'shipping.postcode');
    await postcodeField.fill('1111aa');
    await expect(postcodeField.getLocator()).toHaveValue('1111AA');
  });
});
