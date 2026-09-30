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
 * Dispatching the full checkout page is expensive, so every test dispatches only once with all configurable fields
 * set to required, and then all components that depend on this are checked: Customer and shipping fields with the
 * default skin, billing fields with the onestep skin (with a separate billing address). Note that ConfigFixture
 * attributes are only picked up at method level, not at class level.
 */
#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
final class RequiredFieldsPageTest extends LokiCheckoutTestCase
{
    #[ConfigFixture('customer/address/company_show', 'req', 'store', 'default')]
    #[ConfigFixture('customer/address/prefix_show', 'req', 'store', 'default')]
    #[ConfigFixture('customer/address/taxvat_show', 'req', 'store', 'default')]
    #[ConfigFixture('customer/address/dob_show', 'req', 'store', 'default')]
    #[ConfigFixture('customer/address/gender_show', 'req', 'store', 'default')]
    public function testComponentsOnPage(): void
    {
        $this->dispatchToCheckout();

        $this->assertComponentsOnPage([
            'loki-checkout.customer.customer_dob',
            'loki-checkout.customer.customer_gender',
            'loki-checkout.shipping.address.company',
            'loki-checkout.shipping.address.prefix',
            'loki-checkout.shipping.address.vat_id',
        ]);
    }

    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    #[ConfigFixture('customer/address/company_show', 'req', 'store', 'default')]
    public function testBillingComponentsOnPage(): void
    {
        $this->dispatchToCheckout();

        $this->assertComponentsOnPage([
            'loki-checkout.billing.address.company',
        ]);
    }
}
