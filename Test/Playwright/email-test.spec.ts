import {Field} from '@loki/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki/config';
import merge from '@loki/util/merge';

test.describe('Generic checkout test', function () {
  test('should allow me to go to the checkout', async function ({page, context}) {
    const currentConfig = merge(coreConfig, {
      profile: false,
      config: {
        'customer/address/middlename_show': 1,
        'customer/address/fax_show': 'opt',
        'customer/address/taxvat_show': 'opt',
        'checkout/options/enable_guest_checkout_login': 1,
      }
    });

    await setupCheckout(page, context, currentConfig);

    const emailField = new Field(page, 'customer.email');
    await emailField.fill('info@');
    await expect(emailField.getLocator()).toHaveAttribute('aria-invalid', 'true');

    await emailField.fill('playwright@loki-checkout.com');
    await expect(emailField.getLocator()).toHaveAttribute('aria-invalid', 'true');

    await emailField.fill('guest@loki-checkout.com');
    await expect(emailField.getLocator()).not.toHaveAttribute('aria-invalid');
  });
});
