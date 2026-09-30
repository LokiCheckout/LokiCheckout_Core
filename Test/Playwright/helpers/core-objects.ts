import {expect, type Locator, type Page} from '@playwright/test';

export function getToken(): string {
  const token = process.env.TEST_TOKEN;
  if (!token) {
    throw new Error('TEST_TOKEN is not set');
  }

  return token;
}

export async function configure(page: Page, config: object) {
  const response = await page.request.post(
    '/loki_functional_tests/index/configure?token=' + encodeURIComponent(getToken()),
    {form: {config: JSON.stringify(config)}}
  );

  const data = JSON.parse(await response.text());
  if (data.error) {
    throw new Error('Configure error: ' + data.error);
  }
}

export async function addToCart(page: Page, config: object, qty: number = 1) {
  const response = await page.request.get(
    '/loki_functional_tests/index/addtocart?token=' + encodeURIComponent(getToken())
    + '&qty=' + qty
    + '&config=' + encodeURIComponent(JSON.stringify(config))
  );

  expect(response.ok(), 'Add to cart').toBeTruthy();
}

export async function waitForLokiAjax(page: Page, action: () => Promise<void>) {
  const ajaxDone = page.evaluate(() => new Promise(resolve => {
    document.addEventListener('loki-components.ajax.done', resolve, {once: true});
    setTimeout(resolve, 10000);
  }));

  await action();
  await ajaxDone;
}

export function getShippingAsBillingCheckbox(page: Page): Locator {
  return page.locator('#loki-checkout-billing-address-shipping-as-billing-field');
}

export function getShippingForwardButton(page: Page): Locator {
  return page.locator('#loki-checkout-steps-shipping-forward-button');
}

export function getBillingBackwardButton(page: Page): Locator {
  return page.locator('#loki-checkout-steps-billing-backward-button');
}

export function getPlaceOrderButton(page: Page): Locator {
  return page.locator('#loki-checkout-steps-billing-forward-button');
}

export function getProgressBarStep(page: Page, stepCode: string): Locator {
  return page.locator('nav[aria-label="Pagination"] button[data-step="' + stepCode + '"]');
}

export async function getFormKey(page: Page): Promise<string> {
  const formKey = (await page.context().cookies()).find(cookie => cookie.name === 'form_key')?.value;
  expect(formKey, 'form_key cookie').toBeTruthy();

  return formKey as string;
}
