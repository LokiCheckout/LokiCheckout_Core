# Yireo LokiCheckout

**A developer-friendly Magento 2 checkout based on Magewire**

### Installation
First of all, make sure you have access to the Yireo composer repository. Next, add it via:
```bash
composer config repositories.yireo composer https://composer.yireo.com/
```

You can now install any Loki Checkout composer package via composer:
```bash
composer require yireo/magento2-loki-checkout
```

Enable the module:
```bash
bin/magento module:enable Yireo_LokiCheckout
```

There is no setup script included with this checkout, so there is no point in running `setup:upgrade`.

### Additional steps under Hyvä
Make sure to rebuild the Hyvä
```bash
bin/magento hyva:config:generate
```

Next, rebuild the Tailwind CSS of your theme:
```bash
cd app/design/frontend/Foo/bar/web/tailwind/
npm run build
```

### Features
- Login button in sidebar
- Adding steps based on XML layout
- Regex-based postcode validation (`zip_codes.xml`)
- Order discount coupon
- Support for configuration of number of street lines
- Preview order completion & order summary in sidebar
- Setting required state on fields as configured in the corresponding EAV field
- Change region field (text-input, select-box) based on country selection
- Change required state of postcode depending on `general/country/optional_zip_countries`
- Step validation when going forward
- Navigate steps via progress bar
- VAT ID template switch when country is within EU

- Per field validation based on Magewire
