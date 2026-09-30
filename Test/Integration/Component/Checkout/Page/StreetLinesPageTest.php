<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Page;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * Dispatching the full checkout page is expensive, so every test dispatches only once and checks the boundary of the
 * configured number of street lines: For the shipping address with the default skin, for the billing address with the
 * onestep skin (with a separate billing address). One street line is covered by HiddenFieldsPageTest and four street
 * lines by OptionalFieldsPageTest and BillingPageTest. Note that ConfigFixture attributes are only picked up at method
 * level, not at class level.
 */
#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
final class StreetLinesPageTest extends LokiCheckoutTestCase
{
    #[ConfigFixture('customer/address/street_lines', '2')]
    public function testTwoStreetLines(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentsOnPage(
            ['loki-checkout.shipping.address.street1'],
            ['loki-checkout.shipping.address.street2']
        );
    }

    #[ConfigFixture('customer/address/street_lines', '3')]
    public function testThreeStreetLines(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentsOnPage(
            ['loki-checkout.shipping.address.street2'],
            ['loki-checkout.shipping.address.street3']
        );
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    #[ConfigFixture('customer/address/street_lines', '2')]
    public function testTwoStreetLinesForBilling(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentsOnPage(
            ['loki-checkout.billing.address.street1'],
            ['loki-checkout.billing.address.street2']
        );
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    #[ConfigFixture('customer/address/street_lines', '3')]
    public function testThreeStreetLinesForBilling(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentsOnPage(
            ['loki-checkout.billing.address.street2'],
            ['loki-checkout.billing.address.street3']
        );
    }
}
