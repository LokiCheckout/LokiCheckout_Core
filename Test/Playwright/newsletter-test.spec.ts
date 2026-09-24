import {Field} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

test.describe('Newsletter subscription', function () {
  test('should allow me to finalize the checkout when not checking a non-required newsletter', async function ({page, context}) {
    const currentConfig = merge(coreConfig, {
      profile: 'netherlands',
      newsletter: false,
      config: {
        'checkout/options/foo': 1,
      }
    });

    await setupCheckout(page, context, currentConfig);

    await (new ShippingMethod(page, 'flatrate_flatrate')).select();
    await (new PaymentMethod(page, 'checkmo')).select();

    //const newsletterField = new Field(page, 'newsletter');
    //await newsletterField.check();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();
  });
});
