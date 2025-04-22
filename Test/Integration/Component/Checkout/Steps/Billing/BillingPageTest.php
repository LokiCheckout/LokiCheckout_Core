<?php

declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Steps\Billing;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\GetObjectManager;
use Yireo\LokiCheckout\Test\Fixture\CurrentCheckoutStepFixture;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[ConfigFixture('yireo_loki_checkout/general/theme', 'default')]
#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
//#[DataFixture(CurrentCheckoutStepFixture::class, ['step' => 'billing'])]
#[AppArea('frontend')]
final class BillingPageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    public const BLOCK_NAME = 'loki-checkout.steps.billing';

    final public function testComponentOnPage(): void
    {
        $this->markTestIncomplete('WIP');
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }
}
