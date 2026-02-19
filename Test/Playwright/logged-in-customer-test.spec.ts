import {Field, PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki/checkout-objects';
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

    await expect(page.locator('#loki-checkout-login-form')).toContainText('You are logged in as');
  });

  test('modal login', async function({page, context})  {
    await setupCheckout(page, context, {
      ...coreConfig
    });

    await page.locator('#loki-checkout-login-form-button').click();

    await page.locator('#loki-checkout-login-form-inner-email').fill('playwright@loki-checkout.com');
    await page.locator('#loki-checkout-login-form-inner-password').fill('P@ssw0rd1234');

    await page.getByLabel('Sign In').click();

    await expect(page.locator('#loki-checkout-login-form')).toContainText('You are logged in as');
  });
});
