import AxeBuilder from '@axe-core/playwright'; // 1
import {test, expect} from '@loki/test';
import {setupCheckout} from "@loki/setup-checkout";
import coreConfig from '@loki-checkout/config';

test.describe('checkout', () => {
    test('should not have any automatically detectable accessibility issues', async ({ page, context }) => {
      await setupCheckout(page, context, {
        ...coreConfig,
      });

        const accessibilityScanResults = await new AxeBuilder({ page })
            .include('.loki-checkout')
            .withTags('wcag22aaa')
            .analyze();
        expect(accessibilityScanResults.violations).toEqual([]);
    });
});
