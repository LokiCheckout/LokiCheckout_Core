const {Field} = require(process.cwd() + '/helpers/checkout-objects');
const {test, expect} = require(process.cwd() + '/fixtures/checkout-page');

test.describe('Postcode uppercase test', function()  {
  test('should turn postcode into uppercase', async function({page, request})  {
    const field = new Field(page, 'shipping.postcode');
    await field.fill('1111aa');
    await expect(field.getLocator()).toHaveValue('1111AA');
  });
});
