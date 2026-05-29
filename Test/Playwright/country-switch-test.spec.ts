import {Field} from '@loki/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {expect, test} from '@loki/test';
import coreConfig from '@loki/config';

test.describe('country-switch', function () {
  test('should allow me to switch country', async function ({page, context}) {
    await setupCheckout(page, context, {
      ...coreConfig,
      modules: [
        'LokiCheckout_Nl',
        'LokiCheckout_Us',
      ],
    });

    const countryField = new Field(page, 'shipping.country_id');
    const regionField = new Field(page, 'shipping.region');
    const postcodeField = new Field(page, 'shipping.postcode');
    const postcodeLabel = postcodeField.getLabelLocator();

    async function expectCountryFields({
                                         country,
                                         regionRole,
                                         postcodePlaceholder,
                                         postcodeLabelText,
                                       }) {
      await Promise.all([
        page.waitForResponse(response =>
          response.status() === 200
        ),
        await countryField.fill(country)
      ]);

      await expect(regionField.getLocator()).toHaveRole(regionRole);
      await expect(postcodeField.getLocator()).toHaveAttribute(
        'placeholder',
        postcodePlaceholder,
      );
      await expect(postcodeLabel).toHaveText(postcodeLabelText);
    }

    const expectations = [
      {
        country: 'US',
        regionRole: 'combobox',
        postcodePlaceholder: '12345(-9999)',
        postcodeLabelText: 'Zipcode',
      },
      {
        country: 'NL',
        regionRole: 'textbox',
        postcodePlaceholder: '1234 AA',
        postcodeLabelText: 'Postcode',
      },
      {
        country: 'DE',
        regionRole: 'combobox',
        postcodePlaceholder: '',
        postcodeLabelText: 'Zip/Postal Code',
      },
    ];

    for (const expectation of [...expectations]) {
      await expectCountryFields(expectation);
    }
  });
});
