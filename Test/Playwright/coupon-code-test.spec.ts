import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';
import type {Locator, Page} from '@playwright/test';

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
