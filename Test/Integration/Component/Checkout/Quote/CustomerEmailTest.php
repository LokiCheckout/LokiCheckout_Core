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

    public function testComponentOnPage()
    {
        $cart = $this->getCart();
        $this->dispatchToCheckout($cart);

        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertComponentExistsOnPage($component);
    }

    public function testComponentValidationWithoutValue()
    {
        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertCustomerEmailComponentValidation($component);

        $rt = $this->getValidator()->validate($component, '');
        $this->assertFalse($rt, 'Validation did not fail for empty value');
        $this->assertComponentHasErrors($component);
        $this->assertComponentHasError($component, 'This value is required');
    }

    public function testComponentValidationWithValue()
    {
        $value = 'john@example.com';

        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertCustomerEmailComponentValidation($component);

        $rt = $this->getValidator()->validate($component, $value);
        $this->assertTrue($rt, 'Validation failed for value: '.$value);
        $this->assertComponentHasNoErrors($component);
    }


    public function testComponentValidationWithInvalidValue()
    {
        $value = 'john';

        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertCustomerEmailComponentValidation($component);

        $rt = $this->getValidator()->validate($component, $value);
        $this->assertFalse($rt, 'Validation did not fail for value: '.$value);
        $this->assertComponentHasErrors($component);
        $this->assertComponentHasError($component, 'Invalid email');
    }

    private function assertCustomerEmailComponentValidation(ComponentInterface $component)
    {
        $this->assertContains('required', $component->getValidators());
        $this->assertContains('email', $component->getValidators());
        $this->assertTrue($component->getViewModel()->isRequired());
    }
}
