import {Field, PaymentMethod, PlaceOrderButton, ShippingMethod, SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import type {Page} from '@playwright/test';
import {configure, getShippingAsBillingCheckbox, waitForLokiAjax} from './helpers/core-objects';

const billingConfig = merge(coreConfig, {
  profile: 'netherlands',
  config: {
    'loki_checkout/fields/enable_same_as_billing': 1,
  }
});

const billingFields = {
  'billing.country_id': 'NL',
  'billing.firstname': 'John',
  'billing.lastname': 'Billing',
  'billing.company': 'Billing Company',
  'billing.street0': 'Billingstraat',
  'billing.street1': '42',
  'billing.postcode': '1234 AB',
  'billing.city': 'Amsterdam',
  'billing.telephone': '0612345678',
};

async function useSeparateBillingAddress(page: Page) {
  const checkbox = getShippingAsBillingCheckbox(page);
  await expect(checkbox).toBeVisible();
  await expect(checkbox).toBeChecked();

  await waitForLokiAjax(page, () => checkbox.uncheck());
  await expect(checkbox).not.toBeChecked();
  await expect(new Field(page, 'billing.firstname').getLocator()).toBeVisible();
}

async function fillBillingFields(page: Page) {
  for (const [fieldName, fieldValue] of Object.entries(billingFields)) {
    const field = new Field(page, fieldName);
    await field.fill(fieldValue);
    await field.expectValue(fieldValue);
  }
}

test.describe('Billing address', function () {
  test('should use the shipping address as billing address by default', async function ({page, context}) {
    await setupCheckout(page, context, billingConfig);

    await expect(getShippingAsBillingCheckbox(page)).toBeChecked();
    await expect(new Field(page, 'billing.firstname').getLocator()).toHaveCount(0);
  });

  test('should show the billing address form when unchecking same address', async function ({page, context}) {
    await setupCheckout(page, context, billingConfig);
    await useSeparateBillingAddress(page);

    await waitForLokiAjax(page, () => getShippingAsBillingCheckbox(page).check());
    await expect(getShippingAsBillingCheckbox(page)).toBeChecked();
    await expect(new Field(page, 'billing.firstname').getLocator()).toHaveCount(0);
  });

  test('should keep a separate billing address after reloading', async function ({page, context}) {
    await setupCheckout(page, context, billingConfig);
    await useSeparateBillingAddress(page);
    await fillBillingFields(page);

    await page.reload();

    await expect(getShippingAsBillingCheckbox(page)).not.toBeChecked();
    await expect(new Field(page, 'billing.firstname').getLocator()).toHaveValue('John');
    await expect(new Field(page, 'billing.city').getLocator()).toHaveValue('Amsterdam');
    await expect(new Field(page, 'shipping.firstname').getLocator()).not.toHaveValue('John');
  });

  test('should allow me to place an order with a separate billing address', async function ({page, context}) {
    await setupCheckout(page, context, billingConfig);
    await useSeparateBillingAddress(page);
    await fillBillingFields(page);

    await (new ShippingMethod(page, 'flatrate_flatrate')).select();
    await waitForLokiAjax(page, () => (new PaymentMethod(page, 'checkmo')).select());

    await (new PlaceOrderButton(page)).click();
    await new SuccessPage(page).expectToBeLoaded();
  });
});

test.describe('Billing address without same address toggle', function () {
  test.afterEach(async function ({page}) {
    await configure(page, {config: {'loki_checkout/fields/enable_same_as_billing': 1}});
  });

  test('should hide the same address checkbox when disabled', async function ({page, context}) {
    await setupCheckout(page, context, merge(billingConfig, {
      config: {
        'loki_checkout/fields/enable_same_as_billing': 0,
      }
    }));

    await expect(getShippingAsBillingCheckbox(page)).toHaveCount(0);
  });
});
