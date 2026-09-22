import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test} from '@loki/test';
import coreConfig from '@loki/config';
import merge from '@loki/util/merge';

test.describe('basic address', function () {
  const currentConfig = merge(coreConfig, {
    profile: false,
    config: {
      'customer/address/middlename_show': 1,
      'customer/address/fax_show': 'opt',
      'customer/address/taxvat_show': 'opt',
    }
  });

  test('should allow me to fill all fields', async function ({page,context}) {
    await setupCheckout(page, context, currentConfig);

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
      const field = new Field(page, fieldName);
      await field.fill(fieldValue);
      await field.expectValue(fieldValue);
    }
  });
});
