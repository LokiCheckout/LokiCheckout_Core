import {Field, PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki/config';

test.describe('logged-in customer', function()  {
  test('login via AJAX', async function({page, context})  {
    await setupCheckout(page, context, {
      ...coreConfig,
      customer: {
        ...coreConfig.customer,
        login: true
      }
    });

    await expect(page.locator('#loki-checkout-login')).toContainText('You are logged in as');
  });

  test('modal login', async function({page, context})  {
    await setupCheckout(page, context, {
      ...coreConfig
    });

    await page.locator('#loki-checkout-login-form-button').click();

    const modal = page.locator('#loki-checkout-login-form-modal');
    await expect(modal).toBeVisible();

    await page.locator('#loki-checkout-login-form-inner-email').fill('playwright@loki-checkout.com');
    await page.locator('#loki-checkout-login-form-inner-password').fill('P@ssw0rd1234');

    const button = page.locator('#loki-checkout-login-form-inner-button');
    await expect(button).toBeEnabled();
    await button.click();

    await page.waitForSelector('#loki-checkout-login-form-inner-button', { state: 'detached' });
    await expect(modal).toHaveCount(0);

    await expect(page.locator('#loki-checkout-login')).toContainText(/You are logged in as/);
  });
});
