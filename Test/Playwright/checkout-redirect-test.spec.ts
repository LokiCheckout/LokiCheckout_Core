import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import {addToCart, configure} from './helpers/core-objects';

const guestCheckoutDisabledConfig = merge(coreConfig, {
  profile: false,
  config: {
    'checkout/options/guest_checkout': 0,
  }
});

test.describe('Checkout redirects', function () {
  test.afterEach(async function ({page}) {
    await configure(page, {config: {'checkout/options/guest_checkout': 1}});
  });

  test('should redirect to the cart when the cart is empty', async function ({page}) {
    await configure(page, {config: {...coreConfig.config, 'checkout/options/guest_checkout': 1}});

    await page.goto('/checkout');

    await expect(page).toHaveURL(/\/checkout\/cart\/?$/);
    await expect(page.locator('#loki-checkout')).toHaveCount(0);
  });

  test('should redirect guests to the cart when guest checkout is disabled', async function ({page}) {
    await configure(page, guestCheckoutDisabledConfig);
    await addToCart(page, guestCheckoutDisabledConfig);

    await page.goto('/checkout');

    await expect(page).toHaveURL(/\/checkout\/cart\/?$/);
    await expect(page.locator('#loki-checkout')).toHaveCount(0);
    await expect(page.getByText('Guest checkout is disabled.')).toBeVisible();
  });
});
