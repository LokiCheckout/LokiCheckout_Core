const {Field} = require(process.cwd() + '/helpers/checkout-objects');
const {setupCheckout} = require(process.cwd() + '/helpers/setup-checkout');
const {test, expect} = require(process.cwd() + '/node_modules/@playwright/test');

test.describe('Postcode uppercase test', function()  {
  test('should turn postcode into uppercase', async function({page, context})  {
    await setupCheckout(page, context, {});

    const field = new Field(page, 'shipping.postcode');
    await field.fill('1111aa');
    await expect(field.getLocator()).toHaveValue('1111AA');
  });
});
