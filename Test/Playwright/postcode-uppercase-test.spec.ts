const {FieldManager} = require(process.cwd() + '/helpers/field');
const {saveCheckoutConfig} = require(process.cwd() + '/helpers/checkout-config');
const {test, expect} = require(process.cwd() + '/fixtures/checkout-page');

test.describe('Postcode uppercase test', function()  {
  test('should turn postcode into uppercase', async function({checkoutPage, request})  {
    await checkoutPage.reload();

    const fieldManager = new FieldManager(checkoutPage);
    const field = fieldManager.getField('shipping.postcode');
    await field.fill('1111aa');
    await expect(field.getLocator()).toHaveValue('1111AA');
  });
});
