<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Magewire\Checkout\Billing;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Cache;
use Magento\TestFramework\Fixture\Config;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Magewire\Checkout\Billing\BillingAsShipping;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[
    AppArea('frontend'),
    DataFixture(ProductFixture::class, ['price' => 100], as: 'p1'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$p1.id$','qty' => 1])
]
class BillingAsShippingTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.billing-address.billing-as-shipping';

    #[Config('yireo_loki_checkout/fields/enable_same_as_billing', 0, 'store', 'default')]
    #[Cache('layout', false)]
    public function testDisabled()
    {
        $cart = $this->getCart();
        $this->dispatchToCheckout($cart);
        $this->assertMagewireComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('yireo_loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    #[Cache('layout', false)]
    public function testEnabled()
    {
        $cart = $this->getCart();
        $this->dispatchToCheckout($cart);
        $this->assertMagewireComponentExistsOnPage(self::BLOCK_NAME);
    }

    public function testDefaultValue()
    {
        $component = $this->getDummyComponentInstance(BillingAsShipping::class);

    }
}
