<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Page;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\SalesRule\Test\Fixture\Rule as SalesRuleFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;

/**
 * Dispatching the full checkout page is expensive, so the page is dispatched only once (with the default configuration)
 * and then all components that depend on that configuration are checked. Note that ConfigFixture attributes are only
 * picked up at method level, not at class level.
 */
#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
final class DefaultPageTest extends LokiCheckoutTestCase
{
    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    #[ConfigFixture('checkout/options/enable_agreements', '0', 'store', 'default')]
    public function testComponentsOnPage(): void
    {
        $this->dispatchToCheckout();

        $this->assertComponentsOnPage(
            [
                'loki-checkout.steps',
                'loki-checkout.steps.shipping',
                'loki-checkout.steps.shipping.forward-button',
                'loki-checkout.progressbar',
                'loki-checkout.customer.customer_email',
                'loki-checkout.newsletter',
                'loki-checkout.shipping.address',
                'loki-checkout.shipping.address.firstname',
                'loki-checkout.shipping.address.lastname',
                'loki-checkout.shipping.address.street0',
                'loki-checkout.shipping.address.postcode',
                'loki-checkout.shipping.address.city',
                'loki-checkout.shipping.address.region',
                'loki-checkout.shipping.address.country_id',
                'loki-checkout.shipping.address.telephone',
                'loki-checkout.shipping.methods',
                'loki-checkout.sidebar',
                'loki-checkout.sidebar.items',
                'loki-checkout.sidebar.totals',
                'loki-checkout.sidebar.preview.billing_address',
                'loki-checkout.sidebar.preview.payment_details',
                'loki-checkout.sidebar.preview.quote_details',
                'loki-checkout.sidebar.preview.shipping_details',
            ],
            [
                'loki-checkout.steps.shipping.backward-button',
                'loki-checkout.agreements',
                'loki-checkout.recaptcha.place-order',
                // Only rendered when an active coupon-based cart price rule exists
                'loki-checkout.sidebar.coupon_code',
            ]
        );

        // Prevent duplicate DOM IDs
        $idAttribute = 'id="loki-checkout-steps-shipping"';
        $this->assertEquals(1, substr_count($this->getResponse()->getBody(), $idAttribute), 'Duplicate DOM ID');
    }

    #[ConfigFixture('loki_checkout/general/theme', 'default', 'store', 'default')]
    #[DataFixture(ProductFixture::class, as: 'product')]
    #[DataFixture(GuestCartFixture::class, as: 'cart')]
    #[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
    #[DataFixture(SalesRuleFixture::class, ['coupon_code' => 'loki-test-coupon', 'website_ids' => [1], 'customer_group_ids' => [0, 1]])]
    public function testCouponCodeOnPageWithActiveCouponRule(): void
    {
        $this->dispatchToCheckout();
        $this->assertComponentsOnPage(['loki-checkout.sidebar.coupon_code']);
    }
}
