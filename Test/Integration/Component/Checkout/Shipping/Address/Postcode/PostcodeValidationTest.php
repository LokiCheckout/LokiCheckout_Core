<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\Postcode;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Fixture\ShippingAddressFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutPageTestCase;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    DataFixture(ShippingAddressFixture::class, ['cart_id' => '$cart.id$', 'country_id' => 'NL']),
    AppArea('frontend'),
]
class PostcodeValidationTest extends LokiCheckoutPageTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.postcode';

    /**
     * @dataProvider getTestData
     */
    public function testComponentValidation(
        string $value,
        bool $expectedResult,
        array $expectedErrors
    ): void {
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['required'], $component);
        $this->assertComponentValidation($expectedResult, $expectedErrors, $component, $value);
    }

    public function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['3743KJ', true, []],
            ['3743', false, ['needs to be in one of the following formats']],
        ];
    }
}
