const {Field} = require(process.cwd() + '/helpers/checkout-objects');
const {saveCheckoutConfig} = require(process.cwd() + '/helpers/save-checkout-config');
const {test, expect} = require(process.cwd() + '/fixtures/checkout-page');

test.describe('Toggle regions test', function()  {
  test('should switch region field on country change', async function({page, context})  {
    await saveCheckoutConfig(context, {
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
