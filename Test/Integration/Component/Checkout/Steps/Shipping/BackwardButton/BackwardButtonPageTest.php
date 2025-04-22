<?php

declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Steps\Shipping\BackwardButton;

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

final class BackwardButtonPageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    public const BLOCK_NAME = 'loki-checkout.steps.shipping.backward-button';

    #[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[AppArea('frontend')]
    final public function testComponentNotOnPage(): void
    {
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME, true);
    }

    #[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[DataFixture(CurrentCheckoutStepFixture::class, ['step' => 'billing'])]
    final public function testComponentOnPage(): void
    {
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME, true);
    }
}
