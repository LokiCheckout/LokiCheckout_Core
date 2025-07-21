<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Customer\CustomerGender;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutPageTestCase;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class CustomerGenderPageTest extends LokiCheckoutPageTestCase
{
    const BLOCK_NAME = 'loki-checkout.customer.customer_gender';

    #[ConfigFixture('customer/address/gender_show', 'opt', 'store', 'default')]
    public function testComponentOnPageIfOptional(): void
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }

    #[ConfigFixture('customer/address/gender_show', 'req', 'store', 'default')]
    public function testComponentOnPageIfRequired(): void
    {
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }

    #[ConfigFixture('customer/address/gender_show', 0, 'store', 'default')]
    public function testComponentNotOnPage(): void
    {
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }
}
