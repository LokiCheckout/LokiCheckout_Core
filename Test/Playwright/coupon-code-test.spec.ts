import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import type {Locator, Page} from '@playwright/test';
import {PaymentMethod, ShippingMethod} from '@loki-checkout/checkout-objects';

const couponCode = 'LOKI-PLAYWRIGHT_10';

const couponConfig = merge(coreConfig, {
  profile: 'netherlands',
  coupon_rule: {
    code: couponCode,
  },
});

function getCouponComponent(page: Page): Locator {
  return page.locator('[x-data="LokiCheckoutCouponCodeComponent"]');
}

async function openCouponForm(page: Page): Promise<Locator> {
  const component = getCouponComponent(page);
  await component.locator('summary').click();

  const input = component.locator('input[name="coupon_code"]');
  await expect(input).toBeVisible();
  await expect(input).toHaveAttribute('data-type', 'field');

  return input;
}

function trackLokiRequests(page: Page): string[] {
  const requests: string[] = [];
  page.on('request', request => {
    if (/loki_components\/index\/html/.test(request.url()) && (request.postData() ?? '').includes('coupon_code')) {
      requests.push(request.url());
    }
  });

  return requests;
}

async function applyCouponCode(page: Page, value: string) {
  const input = await openCouponForm(page);
  await input.fill(value);
  await getCouponComponent(page).getByRole('button', {name: 'Apply'}).click();

  return input;
}

test.describe('Coupon code client-side validation', function () {
  for (const [label, value] of [['empty', ''], ['whitespace-only', '   '], ['illegal', 'abc$%<script>']]) {
    test(`should not submit an ${label} coupon code`, async function ({page, context}) {
      await setupCheckout(page, context, couponConfig);
      const requests = trackLokiRequests(page);

      const input = await applyCouponCode(page, value);

      await expect(input).toHaveAttribute('aria-invalid', 'true');
      await expect(getCouponComponent(page)).toContainText('Please enter a valid coupon code');
      await page.waitForTimeout(1000);
      expect(requests).toHaveLength(0);
    });
  }

  test('should clear aria-invalid when typing again', async function ({page, context}) {
    await setupCheckout(page, context, couponConfig);

    const input = await applyCouponCode(page, 'abc$');
    await expect(input).toHaveAttribute('aria-invalid', 'true');

    await input.pressSequentially('x');
    await expect(input).not.toHaveAttribute('aria-invalid', 'true');
  });

  test('should submit a valid coupon code', async function ({page, context}) {
    await setupCheckout(page, context, couponConfig);

    const response = page.waitForResponse(/loki_components\/index\/html/);
    await applyCouponCode(page, `  ${couponCode}  `);
    await response;

    await expect(getCouponComponent(page)).toContainText(couponCode);
  });
});

test.describe('Coupon code updates the rest of the checkout', function () {
  async function submitCouponCode(page: Page, value: string) {
    const response = page.waitForResponse(/loki_components\/index\/html/);
    await applyCouponCode(page, value);
    await response;
  }

  async function removeCouponCode(page: Page) {
    const response = page.waitForResponse(/loki_components\/index\/html/);
    await getCouponComponent(page).getByRole('button', {name: 'Remove'}).click();
    await response;
  }

  test('should update shipping methods and totals when applying a free shipping coupon', async function ({page, context}) {
    const freeShippingCode = 'LOKI-PLAYWRIGHT-FREESHIP';
    await setupCheckout(page, context, merge(coreConfig, {
      profile: 'netherlands',
      config: {'carriers/flatrate/active': '1', 'carriers/flatrate/price': '5'},
      coupon_rule: {code: freeShippingCode, discount: 0, free_shipping: 1},
    }));

    await new ShippingMethod(page, 'flatrate_flatrate').select();
    const shippingTotal = page.locator('dt:has-text("Shipping & Handling") + dd');
    await expect(shippingTotal).toContainText('5.00');

    await submitCouponCode(page, freeShippingCode);

    await expect(getCouponComponent(page)).toContainText(freeShippingCode);
    await expect(shippingTotal).toContainText('0.00');
    await expect(page.getByRole('radio', {name: /Flat Rate/})).toHaveAccessibleName(/Free/);

    await removeCouponCode(page);

    await expect(shippingTotal).toContainText('5.00');
  });

  test('should switch to the free payment method when the grand total becomes zero', async function ({page, context}) {
    const freeOrderCode = 'LOKI-PLAYWRIGHT-FREEORDER';
    await setupCheckout(page, context, merge(coreConfig, {
      profile: 'netherlands',
      config: {'carriers/flatrate/active': '1', 'payment/checkmo/active': '1', 'payment/free/active': '1'},
      coupon_rule: {code: freeOrderCode, discount: 100, apply_to_shipping: 1},
    }));

    await new ShippingMethod(page, 'flatrate_flatrate').select();
    await new PaymentMethod(page, 'checkmo').select();

    const checkmo = new PaymentMethod(page, 'checkmo').getLocator();
    const free = new PaymentMethod(page, 'free').getLocator();
    await expect(free).toHaveCount(0);

    await submitCouponCode(page, freeOrderCode);

    await expect(page.locator('dt:has-text("Grand Total") + dd')).toContainText('0.00');
    await expect(checkmo).toHaveCount(0);
    await expect(free).toBeChecked();

    await removeCouponCode(page);

    await expect(free).toHaveCount(0);
    await expect(checkmo).toBeVisible();
  });
});
