import AxeBuilder from '@axe-core/playwright'; // 1
import {test, expect} from '@loki/test';
import {setupCheckout} from "@loki/setup-checkout";
import coreConfig from '@loki-checkout/config';
import merge from '@loki/util/merge';

test.describe('checkout', () => {
    test('should not have any automatically detectable accessibility issues', async ({ page, context }) => {
      await setupCheckout(page, context, merge(coreConfig, {
        config: {
          'customer/address/street_lines': 2,
        }
      }));

        await expect(page.locator('#loki-checkout-shipping-address-street1-field')).toHaveAttribute(
            'aria-label',
            'Street Address Line 2'
        );

        const accessibilityScanResults = await new AxeBuilder({ page })
            .include('.loki-checkout')
            .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa', 'wcag22aaa'])
            .analyze();
        expect(accessibilityScanResults.violations).toEqual([]);
    });
});
