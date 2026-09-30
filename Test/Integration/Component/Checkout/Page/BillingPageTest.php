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
 * Dispatching the full checkout page is expensive, so the page is dispatched only once with the onestep skin (with a
 * separate billing address and all optional billing fields enabled) and then all billing and payment components are
 * checked. Note that ConfigFixture attributes are only picked up at method level, not at class level.
 */
#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
final class BillingPageTest extends LokiCheckoutTestCase
{
    #[ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default')]
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', '0', 'store', 'default')]
    #[ConfigFixture('customer/address/company_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/prefix_show', 'opt', 'store', 'default')]
    #[ConfigFixture('customer/address/taxvat_show', '1', 'store', 'default')]
    #[ConfigFixture('customer/address/middlename_show', '1', 'store', 'default')]
    #[ConfigFixture('customer/address/suffix_show', '1', 'store', 'default')]
    #[ConfigFixture('customer/address/fax_show', '1', 'store', 'default')]
    #[ConfigFixture('customer/address/street_lines', '4')]
    #[ConfigFixture('payment/checkmo/active', '1', 'store', 'default')]
    #[ConfigFixture('payment/banktransfer/active', '1', 'store', 'default')]
    public function testComponentsOnPage(): void
    {
        $this->dispatchToCheckout();

        $this->assertComponentsOnPage(
            [
                'loki-checkout.billing.address',
                'loki-checkout.billing.address.company',
                'loki-checkout.billing.address.prefix',
                'loki-checkout.billing.address.firstname',
                'loki-checkout.billing.address.middlename',
                'loki-checkout.billing.address.lastname',
                'loki-checkout.billing.address.suffix',
                'loki-checkout.billing.address.street0',
                'loki-checkout.billing.address.street1',
                'loki-checkout.billing.address.street2',
                'loki-checkout.billing.address.street3',
                'loki-checkout.billing.address.postcode',
                'loki-checkout.billing.address.city',
                'loki-checkout.billing.address.region',
                'loki-checkout.billing.address.country_id',
                'loki-checkout.billing.address.telephone',
                'loki-checkout.billing.address.fax',
                'loki-checkout.billing.address.vat_id',
                'loki-checkout.payment.methods',
            ],
            [
                'loki-checkout.billing.address.shipping-as-billing',
            ]
        );
    }
}
