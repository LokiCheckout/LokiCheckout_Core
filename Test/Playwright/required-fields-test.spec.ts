import {Field, PlaceOrderButton, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import {getFormKey, getPlaceOrderButton} from './helpers/core-objects';

const emptyConfig = merge(coreConfig, {
  profile: false,
  config: {
    'loki_checkout/general/theme': 'onestep',
  }
});

test.describe('Required fields', function () {
  test('should disable the place order button when required fields are missing', async function ({page, context}) {
    await setupCheckout(page, context, emptyConfig);

    await expect(getPlaceOrderButton(page)).toHaveAttribute('aria-disabled', 'true');
  });

  test('should not allow me to place an order with missing fields', async function ({page, context}) {
    await setupCheckout(page, context, emptyConfig);

    await (new PlaceOrderButton(page)).clickAndFail();

    await expect(getPlaceOrderButton(page)).toHaveAttribute('aria-disabled', 'true');
    await expect(new Field(page, 'customer.email').getLocator()).toHaveAttribute('aria-invalid', 'true');
    await expect(new Field(page, 'shipping.firstname').getLocator()).toHaveAttribute('aria-invalid', 'true');
  });

  test('should reject the order server-side with missing fields', async function ({page, context}) {
    await setupCheckout(page, context, emptyConfig);

    const formKey = await getFormKey(page);
    await page.goto('/loki_checkout/index/finalize/?form_key=' + encodeURIComponent(formKey));

    await new SuccessPage(page).expectNotToBeLoaded();
    await expect(page).toHaveURL(/\/checkout\/?(#.*)?$/);
  });

  test('should reject the order server-side with an invalid form key', async function ({page, context}) {
    page.setCatchErrors(false);
    await setupCheckout(page, context, merge(coreConfig, {profile: 'netherlands'}));

    await page.goto('/loki_checkout/index/finalize/?form_key=invalid');

    await new SuccessPage(page).expectNotToBeLoaded();
  });
});
