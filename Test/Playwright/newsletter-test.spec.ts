import {Field, ShippingMethod, PaymentMethod, PlaceOrderButton, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import {Locator, Page} from '@playwright/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

const newsletterConfig = (defaultValue: number) => merge(coreConfig, {
  profile: false,
  config: {
    'newsletter/general/active': 1,
    'newsletter/subscription/confirm': 0,
    'loki_checkout/newsletter/default_value': defaultValue,
    'carriers/flatrate/active': 1,
    'payment/checkmo/active': 1,
  }
});

const uniqueEmail = (label: string) => {
  const random = Math.random().toString(36).substring(2, 8);
  return `newsletter-${label}-${Date.now()}-${random}@example.com`;
};

const getNewsletterCheckbox = (page: Page): Locator => page.locator('#loki-checkout-newsletter-field');

const waitForAjax = async (page: Page, action: () => Promise<void>) => {
  const ajaxDone = page.evaluate(() => new Promise(resolve => {
    document.addEventListener('loki-components.ajax.done', resolve, {once: true});
    setTimeout(resolve, 10000);
  }));

  await action();
  await ajaxDone;
};

const fillEmail = async (page: Page, email: string) => {
  const emailField = new Field(page, 'customer.email');
  await emailField.fill(email);
  await expect(emailField.getLocator()).toHaveValue(email);
  await expect(emailField.getLocator()).not.toHaveAttribute('aria-invalid', 'true');
};

const uncheckNewsletter = async (page: Page) => {
  const checkbox = getNewsletterCheckbox(page);
  await expect(checkbox).toBeEnabled();
  await expect(checkbox).toBeChecked();
  await waitForAjax(page, async () => {
    await checkbox.uncheck();
  });
  await expect(checkbox).not.toBeChecked();
};

const reloadCheckout = async (page: Page) => {
  await page.reload();
  await expect(page).toHaveTitle(/Checkout/);
  await expect(getNewsletterCheckbox(page)).toBeVisible();
};

test.describe('Newsletter subscription for guests with the newsletter checked by default', function () {
  test('should keep the newsletter unchecked after unchecking it', async function ({page, context}) {
    await setupCheckout(page, context, newsletterConfig(1));

    const email = uniqueEmail('uncheck');
    await fillEmail(page, email);
    await expect(getNewsletterCheckbox(page)).toBeChecked();

    await uncheckNewsletter(page);
    await page.waitForTimeout(500);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();

    await reloadCheckout(page);
    await expect(new Field(page, 'customer.email').getLocator()).toHaveValue(email);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();
  });

  test('should keep the newsletter unchecked while completing the checkout', async function ({page, context}) {
    await setupCheckout(page, context, merge(newsletterConfig(1), {profile: 'netherlands'}));

    const email = uniqueEmail('order');
    await fillEmail(page, email);
    await expect(getNewsletterCheckbox(page)).toBeChecked();

    await uncheckNewsletter(page);

    await (new ShippingMethod(page, 'flatrate_flatrate')).select();
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();

    await waitForAjax(page, async () => {
      await (new PaymentMethod(page, 'checkmo')).select();
    });
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();

    await (new PlaceOrderButton(page)).click();
    await new SuccessPage(page).expectToBeLoaded();

    await setupCheckout(page, context, merge(newsletterConfig(1), {profile: 'netherlands'}));
    await fillEmail(page, email);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();
  });

  test('should keep the first email unsubscribed after switching emails', async function ({page, context}) {
    await setupCheckout(page, context, newsletterConfig(1));

    const firstEmail = uniqueEmail('first');
    const secondEmail = uniqueEmail('second');

    await fillEmail(page, firstEmail);
    await uncheckNewsletter(page);

    await fillEmail(page, secondEmail);
    await expect(getNewsletterCheckbox(page)).toBeChecked();

    await fillEmail(page, firstEmail);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();

    await reloadCheckout(page);
    await expect(new Field(page, 'customer.email').getLocator()).toHaveValue(firstEmail);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();
  });

  test('should respect a click on the newsletter right after typing a new email', async function ({page, context}) {
    await setupCheckout(page, context, newsletterConfig(1));

    const firstEmail = uniqueEmail('race-first');
    const secondEmail = uniqueEmail('race-second');

    await fillEmail(page, firstEmail);
    await expect(getNewsletterCheckbox(page)).toBeChecked();

    const emailLocator = new Field(page, 'customer.email').getLocator();
    await emailLocator.fill(secondEmail);
    await waitForAjax(page, async () => {
      await getNewsletterCheckbox(page).click();
    });

    await page.waitForTimeout(1000);
    await expect(emailLocator).toHaveValue(secondEmail);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();

    await reloadCheckout(page);
    await expect(emailLocator).toHaveValue(secondEmail);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();

    await fillEmail(page, firstEmail);
    await expect(getNewsletterCheckbox(page)).toBeChecked();
  });
});

test.describe('Newsletter subscription for guests with the newsletter unchecked by default', function () {
  test('should not check the newsletter automatically', async function ({page, context}) {
    await setupCheckout(page, context, newsletterConfig(0));

    await fillEmail(page, uniqueEmail('default-off'));
    await page.waitForTimeout(500);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();

    await reloadCheckout(page);
    await expect(getNewsletterCheckbox(page)).not.toBeChecked();
  });
});
