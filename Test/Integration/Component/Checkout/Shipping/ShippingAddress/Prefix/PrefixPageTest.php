<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingAddress\Prefix;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\AppIsolation;
use Magento\TestFramework\Fixture\Config;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[
    AppIsolation(true),
    DbIsolation(true),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1]),
    AppArea('frontend'),
]
class PrefixPageTest extends LokiCheckoutPageTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.prefix';

    #[Config('customer/address/prefix_show', 'opt', 'store', 'default')]
    public function testComponentOnPageIfOptional(): void
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/prefix_show', 'req', 'store', 'default')]
    public function testComponentOnPageIfRequired(): void
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('customer/address/prefix_show', 0, 'store', 'default')]
    public function testComponentNotOnPage(): void
    {
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }
}
