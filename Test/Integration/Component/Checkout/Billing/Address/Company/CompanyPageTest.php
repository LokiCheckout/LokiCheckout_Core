<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Billing\Address\Company;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

#[DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'weight' => 0], as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class CompanyPageTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.billing.address.company';

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    #[ConfigFixture('customer/address/company_show', 'opt', 'store', 'default')]
    public function testComponentOnPageIfOptional(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, true);
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    #[ConfigFixture('customer/address/company_show', 'req', 'store', 'default')]
    public function testComponentOnPageIfRequired(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    #[ConfigFixture('customer/address/company_show', 0, 'store', 'default')]
    public function testComponentNotOnPage(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
    }
}
