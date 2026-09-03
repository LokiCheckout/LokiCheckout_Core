# LokiCheckout_Core

<!-- badges.specs.start -->
![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Packagist](https://img.shields.io/packagist/v/loki-checkout/magento2-core)
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

## Current status

<!-- badges.test.start -->
[![Static Tests](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/badges/main/pipeline.svg?job=static-tests)](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/-/pipelines)
[![Unit Tests](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/badges/main/pipeline.svg?job=unit-tests)](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/-/pipelines)
[![Integration Tests](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/badges/main/pipeline.svg?job=integration-tests)](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/-/pipelines)
[![Playwright](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/badges/main/pipeline.svg?job=playwright)](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/-/pipelines)
[![DI Compilation](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/badges/main/pipeline.svg?job=compile)](https://gitlab.yireo.com/loki-checkout/LokiCheckout_Core/-/pipelines)
<!-- badges.test.end -->
