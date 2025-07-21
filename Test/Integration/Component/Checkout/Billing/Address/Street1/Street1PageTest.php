<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Billing\Address\Street1;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\GetObjectManager;
use LokiCheckout\Core\Test\Integration\LokiCheckoutPageTestCase;

#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class Street1PageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    const BLOCK_NAME = 'loki-checkout.billing.address.street1';

    #[ConfigFixture('loki_checkout/general/theme', 'onestep')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0')]
    #[ConfigFixture('customer/address/street_lines', '2')]
    final public function testComponentOnPage(): void
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0')]
    #[ConfigFixture('customer/address/street_lines', '1')]
    final public function testComponentNotOnPage(): void
    {
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME, true);
    }
}
