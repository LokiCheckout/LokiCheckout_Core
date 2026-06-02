<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Billing\Address\ToggleSelect;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use LokiCheckout\Core\Test\Fixture\CustomerFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Test\Fixture\CustomerAddressFixture;
use LokiCheckout\Core\Test\Fixture\CustomerLoggedInFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[DbIsolation(true)]
#[DataFixture(CustomerFixture::class, as: 'customer')]
#[DataFixture(CustomerLoggedInFixture::class, ['customer_id' => '$customer.id$'])]
#[DataFixture(CustomerAddressFixture::class, ['customer_id' => '$customer.id$'])]
#[DataFixture(ProductFixture::class, as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class ToggleSelectPageTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.billing.address.toggle-select';

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    public function testComponentOnPage(): void
    {
        $this->markTestSkipped();
        $this->dispatchToCheckout();
        $this->assertCustomerLoggedIn();
        $this->assertCustomerHasAddresses();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, false);
    }
}
