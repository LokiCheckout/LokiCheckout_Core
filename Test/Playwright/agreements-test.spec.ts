import {PaymentMethod, PlaceOrderButton, ShippingMethod, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import type {Locator, Page} from '@playwright/test';

const agreementTitle = 'Agree to terms and conditions';

const agreementConfig = merge(coreConfig, {
  profile: 'netherlands',
  agreement: {
    title: agreementTitle,
    text: 'Actual terms and conditions',
  },
  config: {
    'checkout/options/enable_agreements': 1,
  }
});

function getAgreementCheckbox(page: Page): Locator {
  return page
    .locator('[x-data="LokiCheckoutAgreementComponent"]')
    .filter({hasText: agreementTitle})
    .locator('input[type="checkbox"]');
}

async function waitForLokiAjax(page: Page, action: () => Promise<void>) {
  const response = page.waitForResponse(/loki_components\/index\/html/);
  await action();
  await response;
  await expect((new PlaceOrderButton(page)).getLocator()).not.toHaveAttribute('disabled');
}

async function prepareCheckout(page: Page) {
  await (new ShippingMethod(page, 'flatrate_flatrate')).select();
  await waitForLokiAjax(page, () => (new PaymentMethod(page, 'checkmo')).select());
}

test.describe('Terms and conditions', function () {
  test('should render the terms and conditions checkbox unchecked', async function ({page, context}) {
    await setupCheckout(page, context, agreementConfig);

    const agreementCheckbox = getAgreementCheckbox(page);
    await expect(agreementCheckbox).toBeVisible();
    await expect(agreementCheckbox).not.toBeChecked();
  });

  test('should not allow me to finalize the checkout without agreeing to the terms', async function ({page, context}) {
    await setupCheckout(page, context, agreementConfig);
    await prepareCheckout(page);

    const agreementCheckbox = getAgreementCheckbox(page);
    await expect(agreementCheckbox).toBeVisible();
    if (await agreementCheckbox.isChecked()) {
      await waitForLokiAjax(page, () => agreementCheckbox.uncheck());
    }

    await expect(agreementCheckbox).not.toBeChecked();

    const placeOrderButton = (new PlaceOrderButton(page)).getLocator();
    await placeOrderButton.click({force: true});

    const reachedSuccessPage = await page
      .waitForURL(/checkout\/onepage\/success/, {timeout: 10000})
      .then(() => true)
      .catch(() => false);

    expect(reachedSuccessPage, 'Order was placed without agreeing to the terms').toBe(false);
    await expect(placeOrderButton).toHaveAttribute('aria-disabled', 'true');
  });

  test('should reject the order server-side without agreeing to the terms', async function ({page, context}) {
    await setupCheckout(page, context, agreementConfig);
    await prepareCheckout(page);

    await expect(getAgreementCheckbox(page)).not.toBeChecked();

    const formKey = (await context.cookies()).find(cookie => cookie.name === 'form_key')?.value;
    expect(formKey, 'form_key cookie').toBeTruthy();

    await page.goto('/loki_checkout/index/finalize/?form_key=' + encodeURIComponent(formKey as string));

    await new SuccessPage(page).expectNotToBeLoaded();
    await expect(page).toHaveURL(/\/checkout\/?(#.*)?$/);
    await expect(page.getByRole('alert').filter({hasText: 'agree to the terms and conditions'})).toBeVisible();
  });

  test('should allow me to finalize the checkout when agreeing to the terms', async function ({page, context}) {
    await setupCheckout(page, context, agreementConfig);
    await prepareCheckout(page);

    const agreementCheckbox = getAgreementCheckbox(page);
    await expect(agreementCheckbox).toBeVisible();
    if (!(await agreementCheckbox.isChecked())) {
      await waitForLokiAjax(page, () => agreementCheckbox.check());
    }

    await expect(agreementCheckbox).toBeChecked();

    const placeOrderButton = new PlaceOrderButton(page);
    await placeOrderButton.click();

    await new SuccessPage(page).expectToBeLoaded();
  });
});
