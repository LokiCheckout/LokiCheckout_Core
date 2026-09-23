import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

test.describe('Base test', function () {
  test('base checks for default skin', async function ({page, context}) {
    const currentConfig = merge(coreConfig, {
      config: {
        'loki_checkout/general/theme': 'default',
      }
    });

    await setupCheckout(page, context, currentConfig);

    await expect(page.locator('#loki-checkout')).toBeVisible();
    await expect(page.locator('#loki-checkout')).toHaveCount(1);
  });

  test('base checks for onestep skin', async function ({page, context}) {
    const currentConfig = merge(coreConfig, {
      config: {
        'loki_checkout/general/theme': 'onestep',
      }
    });

    await setupCheckout(page, context, currentConfig);

    await expect(page.locator('#loki-checkout')).toBeVisible();
    await expect(page.locator('#loki-checkout')).toHaveCount(1);
  });
});
