<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Billing;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Cache;
use Magento\TestFramework\Fixture\Config;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertStoreConfigValueEquals;
use Yireo\LokiCheckout\Component\Checkout\Billing\BillingAsShipping;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

#[
    AppArea('frontend'),
    DataFixture(ProductFixture::class, ['price' => 100], as: 'p1'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$p1.id$', 'qty' => 1])
]
class BillingAsShippingTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.billing-address.billing-as-shipping';

    use AssertStoreConfigValueEquals;

    #[Config('yireo_loki_checkout/fields/enable_same_as_billing', 0, 'store', 'default')]
    public function testDisabled()
    {
        $cart = $this->getCart();
        $this->dispatchToCheckout($cart);
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }

    #[Config('yireo_loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    public function testEnabled()
    {
        $cart = $this->getCart();
        $this->dispatchToCheckout($cart);
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }
}
