import {Field} from '@helpers/checkout-objects';
import {setupCheckout} from '@helpers/setup-checkout';
import {test, expect} from '@playwright/test';

test.describe('Postcode uppercase test', function()  {
  test('should turn postcode into uppercase', async function({page, context})  {
    await setupCheckout(page, context, {});

    const field = new Field(page, 'shipping.postcode');
    await field.fill('1111aa');
    await expect(field.getLocator()).toHaveValue('1111AA');
  });
});
