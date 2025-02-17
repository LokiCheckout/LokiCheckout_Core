<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingAddress;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;

// @todo: Allow reusing fixtures
#[
    AppArea('frontend'),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1])
]
// @todo: Test saving to quote
class FirstnameTest extends LokiTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.firstname';

    public function testComponentOnPage()
    {
        $this->dispatchToCheckout();
        $component = $this->getComponent(self::BLOCK_NAME);
        $this->assertComponentExistsOnPage($component);
    }

    /**
     * @param string $value
     * @param bool $expectedResult
     * @param array $expectedErrors
     * @return void
     * @dataProvider getTestData
     */
    public function testComponentValidation(
        string $value,
        bool $expectedResult,
        array $expectedErrors
    ) {
        $component = $this->getComponent(self::BLOCK_NAME);
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
