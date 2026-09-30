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
 * Dispatching the full checkout page is expensive, so the page is dispatched only once with all optional fields and
 * features enabled, and then all components that depend on this are checked. Note that ConfigFixture attributes are
 * only picked up at method level, not at class level.
 */
#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
final class OptionalFieldsPageTest extends LokiCheckoutTestCase
{
    #[ConfigFixture('customer/address/company_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/prefix_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/taxvat_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/dob_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/gender_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/middlename_show', '1', 'store', 'default')]
    #[ConfigFixture('customer/address/suffix_show', '1', 'store', 'default')]
    #[ConfigFixture('customer/address/fax_show', '1', 'store', 'default')]
    #[ConfigFixture('customer/address/street_lines', '4')]
    #[ConfigFixture('checkout/options/enable_agreements', '1', 'store', 'default')]
    public function testComponentsOnPage(): void
    {
        $this->dispatchToCheckout();

        $this->assertComponentsOnPage([
            'loki-checkout.customer.customer_dob',
            'loki-checkout.customer.customer_gender',
            'loki-checkout.shipping.address.company',
            'loki-checkout.shipping.address.prefix',
            'loki-checkout.shipping.address.middlename',
            'loki-checkout.shipping.address.suffix',
            'loki-checkout.shipping.address.vat_id',
            'loki-checkout.shipping.address.fax',
            'loki-checkout.shipping.address.street1',
            'loki-checkout.shipping.address.street2',
            'loki-checkout.shipping.address.street3',
            'loki-checkout.agreements',
        ]);
    }
}
