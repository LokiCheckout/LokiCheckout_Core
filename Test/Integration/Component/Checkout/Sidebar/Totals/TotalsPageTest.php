<?php

declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Sidebar\Totals;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\GetObjectManager;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class TotalsPageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    public const BLOCK_NAME = 'loki-checkout.sidebar.totals';

    final public function testComponentOnPage(): void
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }
}
