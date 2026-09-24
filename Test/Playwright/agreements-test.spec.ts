import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

test.describe('Terms and conditions', function () {
  test('should allow me to finalize the checkout when agreeing to nothing without valid data', async function ({page, context}) {
    const currentConfig = merge(coreConfig, {
      profile: 'netherlands',
      agreement: false,
      config: {
        'checkout/options/enable_agreements': 1,
      }
    });

    await setupCheckout(page, context, currentConfig);

    await (new ShippingMethod(page, 'flatrate_flatrate')).select();
    await (new PaymentMethod(page, 'checkmo')).select();

    const agreementField = new Field(page, 'agreement');
    await agreementField.check();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();
  });

  test('should not allow me to finalize the checkout when agreeing to nothing', async function ({page, context}) {
    const currentConfig = merge(coreConfig, {
      profile: 'netherlands',
      agreement: {
        title: "Agree to terms conditions",
        text: "Actual terms conditions",
      },
      config: {
        'checkout/options/enable_agreements': 1,
      }
    });

    await setupCheckout(page, context, currentConfig);

    await (new ShippingMethod(page, 'flatrate_flatrate')).select();
    await (new PaymentMethod(page, 'checkmo')).select();

    const agreementField = new Field(page, 'agreement');
    await agreementField.check();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();
  });
});
