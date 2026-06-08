<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Checkout;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Test\Integration\Trait\SaveShippingAddressValues;
use LokiCheckout\Core\Util\Checkout\UpdateShippingMethod;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    ConfigFixture('carriers/flatrate/active', 1, 'store', 'default'),
    AppArea('frontend'),
]
class UpdateShippingMethodTest extends LokiCheckoutTestCase
{
    use SaveShippingAddressValues;

    private function getSubject(): UpdateShippingMethod
    {
        return $this->getObjectManager()->get(UpdateShippingMethod::class);
    }

    public function testExecuteSetsShippingMethod(): void
    {
        $this->dispatchToCheckout();
        $this->saveShippingAddressValues();

        $this->getSubject()->execute('flatrate_flatrate');

        $shippingAddress = $this->getQuote()->getShippingAddress();
        $this->assertSame('flatrate_flatrate', $shippingAddress->getShippingMethod());
    }

    public function testExecuteWithEmptyCodeClearsShippingMethod(): void
    {
        $this->dispatchToCheckout();
        $this->saveShippingAddressValues();

        $this->getSubject()->execute('flatrate_flatrate');
        $this->getSubject()->execute('');

        $shippingAddress = $this->getQuote()->getShippingAddress();
        $this->assertSame('', (string)$shippingAddress->getShippingMethod());
    }
}
