<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Customer\CustomerEmail;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class CustomerEmailValidationTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.customer_email';

    public function testComponentValidators(): void
    {
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['required'], $component);
    }

    /**
     * @dataProvider getTestData
     */
    public function testComponentValidation(
        string $value,
        bool $expectedResult,
        array $expectedErrors
    ): void {
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidation($expectedResult, $expectedErrors, $component, $value);
    }

    public function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['john', false, ['Invalid email address']],
            ['john@example.com', true, []],
        ];
    }
}
