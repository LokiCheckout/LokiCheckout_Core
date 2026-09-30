import {Field, PaymentMethod} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import type {Page} from '@playwright/test';
import {
  getBillingBackwardButton,
  getPlaceOrderButton,
  getProgressBarStep,
  getShippingForwardButton,
} from './helpers/core-objects';

const multiStepConfig = merge(coreConfig, {
  profile: 'netherlands',
  config: {
    'loki_checkout/general/theme': 'default',
  }
});

async function expectShippingStep(page: Page) {
  await expect(getShippingForwardButton(page)).toBeVisible();
  await expect(getPlaceOrderButton(page)).toHaveCount(0);
}

async function expectBillingStep(page: Page) {
  await expect(page).toHaveURL(/#billing$/);
  await expect(getPlaceOrderButton(page)).toBeVisible();
  await expect(getShippingForwardButton(page)).toHaveCount(0);
}

async function goToBillingStep(page: Page) {
  const forwardButton = getShippingForwardButton(page);
  await expect(forwardButton).not.toHaveAttribute('aria-disabled', 'true');
  await forwardButton.click();
  await expectBillingStep(page);
}

test.describe('Multi-step navigation', function () {
  test('should start at the shipping step', async function ({page, context}) {
    await setupCheckout(page, context, multiStepConfig);

    await expectShippingStep(page);
    await expect(page.locator('nav[aria-label="Pagination"] li[aria-current="step"]')).toHaveCount(1);
  });

  test('should not allow me to go to the billing step with missing fields', async function ({page, context}) {
    await setupCheckout(page, context, merge(multiStepConfig, {profile: false}));

    const forwardButton = getShippingForwardButton(page);
    await expect(forwardButton).toHaveAttribute('aria-disabled', 'true');

    await forwardButton.click();

    await expect(page).not.toHaveURL(/#billing$/);
    await expectShippingStep(page);
    await expect(new Field(page, 'shipping.firstname').getLocator()).toHaveAttribute('aria-invalid', 'true');
  });

  test('should allow me to go forward and backward between steps', async function ({page, context}) {
    await setupCheckout(page, context, multiStepConfig);

    const firstname = new Field(page, 'shipping.firstname');
    await firstname.fill('Jane');
    await firstname.expectValue('Jane');

    await goToBillingStep(page);
    await expect(new PaymentMethod(page, 'checkmo').getLocator()).toBeVisible();

    const backwardButton = getBillingBackwardButton(page);
    await expect(backwardButton).toBeVisible();
    await backwardButton.click();

    await expectShippingStep(page);
    await expect(new Field(page, 'shipping.firstname').getLocator()).toHaveValue('Jane');
  });

  test('should allow me to go back to the shipping step via the progress bar', async function ({page, context}) {
    await setupCheckout(page, context, multiStepConfig);

    await goToBillingStep(page);

    const shippingStep = getProgressBarStep(page, 'shipping');
    await expect(shippingStep).toBeVisible();
    await shippingStep.click();

    await expectShippingStep(page);
  });

  test('should remain on the billing step after reloading', async function ({page, context}) {
    await setupCheckout(page, context, multiStepConfig);

    await goToBillingStep(page);
    await page.reload();

    await expectBillingStep(page);
  });
});
