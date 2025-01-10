<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Quote;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;
use Yireo\LokiComponents\Component\ComponentInterface;

#[
    AppArea('frontend'),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1])
]
class CustomerEmailTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.customer_email';

    public function testComponentWithoutValue()
    {
        $cart = $this->getCart();
        $cart->getShippingAddress()->addData(['email' => '']);
        $this->dispatchToCheckout($cart);

        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertCustomerEmailComponentIsValid($component);

        $this->getValidator()->validate($component, '');
        $this->assertComponentHasErrors($component);
        $this->assertComponentHasError($component, 'Value is required');
    }

    public function testComponentWithValue()
    {
        $cart = $this->getCart();
        $cart->getShippingAddress()->setData('email', 'john@example.com');
        $this->dispatchToCheckout($cart);

        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertCustomerEmailComponentIsValid($component);

        $this->getValidator()->validate($component, 'john@example.com');
        $this->assertComponentHasNoErrors($component);
    }

    private function assertCustomerEmailComponentIsValid(ComponentInterface $component)
    {
        $this->assertComponentExistsOnPage($component);
        $this->assertContains('email', $component->getValidators());
        $this->assertTrue($component->getViewModel()->isRequired());
    }
}
