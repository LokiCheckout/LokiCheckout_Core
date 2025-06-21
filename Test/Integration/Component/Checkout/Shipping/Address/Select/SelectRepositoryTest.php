<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\Address\Select;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Component\Checkout\Address\Select\SelectRepository;
use Yireo\LokiCheckout\Test\Fixture\CustomerLoggedInFixture;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;

#[
    DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer'),
    DataFixture(CustomerLoggedInFixture::class, ['customer_id' => '$customer.id$']),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class SelectRepositoryTest extends LokiCheckoutPageTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.select';

    public function testGetValue()
    {
        $component = $this->getComponent(self::BLOCK_NAME);

        /** @var SelectRepository $repository */
        $repository = $component->getRepository();
        $value = $repository->getValue();
        $this->assertSame(0, $value);
    }
}
