<?php

declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\Address\View;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\GetObjectManager;
use Yireo\LokiCheckout\Test\Fixture\CustomerAddressFixture;
use Yireo\LokiCheckout\Test\Fixture\CustomerLoggedInFixture;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer')]
#[DataFixture(CustomerLoggedInFixture::class, ['customer_id' => '$customer.id$'])]
#[DataFixture(CustomerAddressFixture::class, ['customer_id' => '$customer.id$'])]
#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class ViewPageTest extends LokiCheckoutPageTestCase
{
    use GetObjectManager;

    const BLOCK_NAME = 'loki-checkout.shipping.address.view';

    final public function testComponentOnPage(): void
    {
        $this->markTestSkipped('Not implemented.');
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }
}
