import {Field} from '@loki/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki/config';

test.describe('Postcode uppercase test', function()  {
  test('should turn postcode into uppercase', async function({page, context})  {
    await setupCheckout(page, context, {
      ...coreConfig
    });

    const field = new Field(page, 'shipping.postcode');
    await field.fill('1111aa');
    await expect(field.getLocator()).toHaveValue('1111AA');
  });
});
