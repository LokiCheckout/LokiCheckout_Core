<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\Prefix;

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
class PrefixValidationTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.prefix';

    /**
     * @dataProvider getTestData
     */
    #[ConfigFixture('customer/address/prefix_show', 'req')]
    #[ConfigFixture('customer/address/prefix_show', 'req', 'store', 'default')]
    public function testComponentValidation(
        string $value,
        bool $expectedResult,
        array $expectedErrors
    ): void {
        $this->dispatchToCheckout();
        $component = $this->getCheckoutComponent(self::BLOCK_NAME);
        $this->assertComponentValidators(['required'], $component);
        $this->assertComponentValidation($expectedResult, $expectedErrors, $component, $value);
    }

    public function getTestData(): array
    {
        return [
            ['', false, ['Value is required']],
            ['John', true, []],
        ];
    }
}
