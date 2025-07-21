<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\BillingAsShipping;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\GetObjectManager;
use LokiCheckout\Core\Test\Integration\LokiCheckoutPageTestCase;

#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class BillingAsShippingPageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    const BLOCK_NAME = 'loki-checkout.shipping.address.billing-as-shipping';

    final public function testComponentOnPage(): void
    {
        $this->markTestSkipped('Not implemented yet');
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }
}
