const {Field} = require(process.cwd() + '/helpers/field');
const {saveCheckoutConfig} = require(process.cwd() + '/helpers/checkout-config');
const {test, expect} = require(process.cwd() + '/fixtures/checkout-page');

test.beforeAll(async ({request}) => {
  await saveCheckoutConfig(request, {
    config: {
      'customer/address/middlename_show': 1
    }
  });
});

test.describe('Generic checkout test', function () {
  test('should allow me to go to the checkout', async function ({checkoutPage}) {
    const fields = {
      'customer.email': 'jane@example.com',
      'shipping.firstname': 'Jane',
      'shipping.middlename': 'Flower',
      'shipping.lastname': 'Doe',
      'shipping.company': 'Yireo',
      'shipping.city': 'Baarn',
      'shipping.street0': 'Amalialaan',
      'shipping.street1': '126',
      'shipping.telephone': '0123456789',
      'shipping.fax': '0123456789',
      'shipping.vat-id': 'NL1234123412',
    };

    for (const [fieldName, fieldValue] of Object.entries(fields)) {
      const field = new Field(checkoutPage, fieldName);
      await field.fill(fieldValue);
      await field.expectValue(fieldValue);
    }
  });
});
