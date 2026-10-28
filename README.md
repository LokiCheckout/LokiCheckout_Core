# LokiCheckout_Core

<!-- badges.specs.start -->
<div class="github-specs-badges">

![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/loki-checkout/magento2-core)

</div>
<!-- badges.specs.end -->


**This is the main core package of the LokiCheckout. It replaces the regular checkout with its own, based on Loki Components, Loki Field Components and much more. All other Loki Checkout module are extending this core module. With only this core module, you already have a working checkout.**

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
