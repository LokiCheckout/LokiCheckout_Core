# LokiCheckout_Core

<!-- badges.specs.start -->
<div class="github-specs-badges">

![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/loki-checkout/magento2-core)

</div>
<!-- badges.specs.end -->


**This is the main core package of the LokiCheckout. It replaces the regular checkout with its own, based on Loki Components, Loki Field Components and much more. All other Loki Checkout modules are extending this core module. With purely this core module, you already have a working checkout.**

## Installation
Install this package via composer:
```bash
composer require loki-checkout/magento2-core
```

Next, enable this module:
```bash
bin/magento module:enable LokiCheckout_Core
```

## Settings
In the Magento Admin Panel, you can navigate to **Store > Configuration > Loki > LokiCheckout** for various settings.

### Overriding settings via the XML layout
Most settings can also be overridden via the XML layout, by adding items (with the configuration path as name) to the
`config` argument of the block `loki-checkout.config`:

```xml
<referenceBlock name="loki-checkout.config">
    <arguments>
        <argument name="config" xsi:type="array">
            <item name="loki_checkout/fields/enable_same_as_billing" xsi:type="boolean">false</item>
            <item name="loki_checkout/cart/max_items_visible_count" xsi:type="number">5</item>
        </argument>
    </arguments>
</referenceBlock>
```

The settings `loki_checkout/general/enable`, `loki_checkout/general/license_key`, `loki_checkout/general/theme`
(skin) and `loki_checkout/general/grid_layout` can not be overridden this way, because they are needed before the layout
is loaded. Overrides only apply once the layout has been generated.

## Google reCAPTCHA
The checkout picks up the regular Magento reCAPTCHA configuration under **Stores > Configuration > Security >
Google reCAPTCHA Storefront**. There is no additional Loki setting involved: as soon as a type is selected for
one of the storefront forms below, and the matching Website Key and Secret Key are filled in, the challenge is
rendered and verified inside the checkout.

Supported form ids: `place_order`, `customer_login`, `newsletter` and `coupon_code`. Supported types: reCAPTCHA
v2 ("I am not a robot"), reCAPTCHA v2 Invisible and reCAPTCHA v3 Invisible.

## Current status

<!-- badges.test.start -->
<div class="github-pipeline-badges">

![Static Tests](https://img.shields.io/github/actions/workflow/status/LokiCheckout/LokiCheckout_Core/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/LokiCheckout/LokiCheckout_Core/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/LokiCheckout/LokiCheckout_Core/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/LokiCheckout/LokiCheckout_Core/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/LokiCheckout/LokiCheckout_Core/compile.yml?label=compile)

</div>
<!-- badges.test.end -->
