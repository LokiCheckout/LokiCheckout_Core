<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Address\SaveInAddressBook;

use LokiCheckout\Core\Test\Fixture\CustomerAddressFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use LokiCheckout\Core\Test\Fixture\CustomerFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use LokiCheckout\Core\Test\Fixture\CustomerLoggedInFixture;

// Fixtures are disabled while this test is skipped, because Magento applies them before the test body runs (which
// is costly). Their imports are kept on purpose, so that re-enabling this test only requires uncommenting.
// #[DbIsolation(true)]
// #[DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer')]
// #[DataFixture(CustomerLoggedInFixture::class, ['customer_id' => '$customer.id$'])]
// #[DataFixture(CustomerAddressFixture::class, ['customer_id' => '$customer.id$'])]
// #[DataFixture(ProductFixture::class, as: 'product')]
// #[DataFixture(GuestCartFixture::class, as: 'cart')]
// #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class SaveInAddressBookPageTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.shipping.address.save_in_address_book';

    final public function testComponentOnPage(): void
    {
        $this->markTestSkipped();
        $this->dispatchToCheckout();
        $this->assertCustomerLoggedIn();
        $this->assertCustomerHasAddresses();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME, false);
    }
}
