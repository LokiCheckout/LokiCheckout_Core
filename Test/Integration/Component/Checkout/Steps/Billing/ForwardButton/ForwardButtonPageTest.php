<?php

declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Steps\Billing\ForwardButton;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use PHPUnit\Framework\TestCase;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\GetObjectManager;
use Yireo\LokiCheckout\Test\Fixture\CurrentCheckoutStepFixture;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1])]
#[AppArea('frontend')]
final class ForwardButtonPageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    public const BLOCK_NAME = 'loki-checkout.steps.billing.forward-button';

    #[ConfigFixture('yireo_loki_checkout/general/theme', 'default')]
    #[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1])]
    #[DataFixture(CurrentCheckoutStepFixture::class, ['step' => 'billing'])]
    final public function testComponentOnPage(): void
    {
        $this->markTestIncomplete('WIP');
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }
}
