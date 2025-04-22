<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\Address\Middlename;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class MiddlenamePageTest extends LokiCheckoutPageTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.middlename';

    #[ConfigFixture('customer/address/middlename_show', 1, 'store', 'default')]
    public function testComponentOnPage(): void
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }

    #[ConfigFixture('customer/address/middlename_show', 0, 'store', 'default')]
    public function testComponentNotOnPage(): void
    {
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }
}
