<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Customer\CustomerDob;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class CustomerDobValidationTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.customer_dob';

    #[ConfigFixture('customer/address/dob_show', 'req', 'store', 'default')]
    #[ConfigFixture('customer/address/dob_show', 'req')]
    public function testComponentValidators(): void
    {
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['required', 'date', 'past_date'], $component);
    }

    #[ConfigFixture('customer/address/dob_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/dob_show', 'opt')]
    public function testComponentNoValidators(): void
    {
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['date', 'past_date'], $component);
        $this->assertComponentNoValidators(['required'], $component);
    }

    /**
     * @dataProvider getTestData
     */
    #[ConfigFixture('customer/address/dob_show', 'req', 'store', 'default')]
    #[ConfigFixture('customer/address/dob_show', 'req')]
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
            ['foobar', false, ['Invalid date format']],
            ['1978-01-02', true, []],
            [((int)date('Y') - 1) . '-01-02', false, ['You need to be at least 10 years old']],
            [((int)date('Y') + 12) . '-01-02', false, ['Date needs to be in the past']],
        ];
    }
}
