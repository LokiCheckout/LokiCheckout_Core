import {Field} from '@loki/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki/config';

test.describe('Toggle regions test', function()  {
  test('should switch region field on country change', async function({page, context})  {
    await setupCheckout(page, context, {
      ...coreConfig,
      config: {
        ...coreConfig.config,
        'loki_checkout/fields/hide_region_when_optional': 0
      }
    });

    const countryField = new Field(page, 'shipping.country_id');
    const regionField = new Field(page, 'shipping.region');

    await countryField.fill('US');
    await expect(regionField.getLocator()).toHaveRole('combobox');
    await regionField.fill('12');
    await expect(regionField.getLocator()).toHaveValue('12');

    await countryField.fill('NL');
    await expect(regionField.getLocator()).toHaveRole('textbox');
    await regionField.fill('Utrecht');
    await expect(regionField.getLocator()).toHaveValue('Utrecht');
  });
});
