<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\City;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutPageTestCase;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class CityValidationTest extends LokiCheckoutPageTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.city';

    /**
     * @dataProvider getTestData
     */
    public function testComponentValidation(
        string $value,
        bool $expectedResult,
        array $expectedErrors
    ): void {
        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['required', 'customerAddress'], $component);
        $this->assertComponentValidation($expectedResult, $expectedErrors, $component, $value);
    }

    public function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['Sin City', true, []],
            ['Sin City #', false, ['Invalid City']],
            ['Sin City 2', false, ['Invalid City']],
        ];
    }
}
