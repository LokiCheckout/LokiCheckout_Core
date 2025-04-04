import {Field} from '@helpers/checkout-objects';
import {setupCheckout} from '@helpers/setup-checkout';
import {test, expect} from '@playwright/test';

test.describe('Toggle regions test', function()  {
  test('should switch region field on country change', async function({page, context})  {
    await setupCheckout(page, context, {
      config: {
        'yireo_loki_checkout/fields/hide_region_when_optional': 0
      }
    });

    const countryField = new Field(page, 'shipping.country_id');
    const regionField = new Field(page, 'shipping.region');

    await countryField.fill('US');
    await expect(regionField.getLocator()).toHaveRole('combobox');
    await regionField.fill('12');
    await expect(regionField.getLocator()).toHaveValue('12');

    await countryField.fill('BJ');
    await expect(regionField.getLocator()).toHaveRole('textbox');
    await regionField.fill('Utrecht');
    await expect(regionField.getLocator()).toHaveValue('Utrecht');
  });
});
