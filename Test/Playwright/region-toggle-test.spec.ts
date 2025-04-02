const {FieldManager} = require(process.cwd() + '/helpers/field');
const {saveCheckoutConfig} = require(process.cwd() + '/helpers/checkout-config');
const {test, expect} = require(process.cwd() + '/fixtures/checkout-page');

test.beforeAll(async ({request}) => {
  await saveCheckoutConfig(request, {
    config: {
      'yireo_loki_checkout/fields/hide_region_when_optional': 0
    }
  });
});

test.describe('Toggle regions test', function()  {
  test('should switch region field on country change', async function({checkoutPage, request})  {
    const fieldManager = new FieldManager(checkoutPage);
    const countryField = fieldManager.getField('shipping.country_id');
    const regionField = fieldManager.getField('shipping.region');

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
