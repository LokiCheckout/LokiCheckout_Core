<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Billing;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Checkout\Test\Fixture\SetBillingAddress as SetBillingAddressFixture;
use Magento\Checkout\Test\Fixture\SetDeliveryMethod as SetDeliveryMethodFixture;
use Magento\Checkout\Test\Fixture\SetGuestEmail as SetGuestEmailFixture;
use Magento\Checkout\Test\Fixture\SetPaymentMethod as SetPaymentMethodFixture;
use Magento\Checkout\Test\Fixture\SetShippingAddress as SetShippingAddressFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertStoreConfigValueEquals;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

// @todo: Move this
/*
Config('carriers/freeshipping/active', '1', 'store', 'default'),
Config('payment/free/active', '1', 'store', 'default'),
DataFixture(SetBillingAddressFixture::class, ['cart_id' => '$cart.id$']),
DataFixture(SetShippingAddressFixture::class, ['cart_id' => '$cart.id$']),
DataFixture(SetGuestEmailFixture::class, ['cart_id' => '$cart.id$']),
DataFixture(
    SetDeliveryMethodFixture::class,
    ['cart_id' => '$cart.id$', 'carrier_code' => 'freeshipping', 'method_code' => 'freeshipping']
),
DataFixture(SetPaymentMethodFixture::class, ['cart_id' => '$cart.id$', 'method' => 'free']),
*/

class BillingAsShippingTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.billing-as-shipping';

    use AssertStoreConfigValueEquals;

    #[
        AppArea('frontend'),
        Config('yireo_loki_checkout/general/onestep', '1', 'store', 'default'),
        DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
        DataFixture(GuestCartFixture::class, as: 'cart'),
        DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1])
    ]
    #[Config('yireo_loki_checkout/fields/enable_same_as_billing', 0, 'store', 'default')]
    public function testDisabled()
    {
        $cart = $this->getCart();
        $this->dispatchToCheckout($cart);
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[
        AppArea('frontend'),
        Config('yireo_loki_checkout/general/onestep', '1', 'store', 'default'),
        DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
        DataFixture(GuestCartFixture::class, as: 'cart'),
        DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1])
    ]
    #[Config('yireo_loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    public function testEnabled()
    {
        $cart = $this->getCart();
        $this->dispatchToCheckout($cart);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }
}
