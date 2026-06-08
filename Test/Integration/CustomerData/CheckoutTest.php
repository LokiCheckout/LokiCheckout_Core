<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\CustomerData;

use LokiCheckout\Core\CustomerData\Checkout;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class CheckoutTest extends LokiCheckoutTestCase
{
    public function testSectionDataWithQuote(): void
    {
        $this->getCart();

        $subject = $this->getObjectManager()->get(Checkout::class);
        $data = $subject->getSectionData();

        $this->assertArrayHasKey('customer', $data);
        $this->assertArrayHasKey('cart_items', $data);
        $this->assertArrayHasKey('shipping_address', $data);
        $this->assertArrayHasKey('billing_address', $data);
        $this->assertArrayHasKey('customer_email', $data);
        $this->assertNotEmpty($data['cart_items']);
    }
}
