<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Billing\Address\ShippingAsBilling;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;

use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertStoreConfigValueEquals;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[
    AppArea('frontend'),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])
]
class ShippingAsBillingPageTest extends LokiCheckoutPageTestCase
{
    const BLOCK_NAME = 'loki-checkout.billing.address.shipping-as-billing';

    use AssertStoreConfigValueEquals;

    #[
        ConfigFixture('yireo_loki_checkout/general/theme', 'onestep'),
        ConfigFixture('yireo_loki_checkout/fields/enable_same_as_billing', 0, 'store', 'default')
    ]
    public function testDisabled()
    {
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[
        ConfigFixture('yireo_loki_checkout/general/theme', 'onestep'),
        ConfigFixture('yireo_loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')
    ]
    public function testEnabled()
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }
}
