<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Billing\Address\ShippingAsBilling;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Util\SameAddressService;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;

use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\ObjectManager;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertStoreConfigValueEquals;

#[
    AppArea('frontend'),
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])
]
class ShippingAsBillingPageTest extends LokiCheckoutTestCase
{
    const BLOCK_NAME = 'loki-checkout.billing.address.shipping-as-billing';

    use AssertStoreConfigValueEquals;

    // The disabled state (enable_same_as_billing=0) is covered by Component\Checkout\Page\BillingPageTest
    #[
        ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default'),
        ConfigFixture('loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')
    ]
    public function testEnabled()
    {
        $this->dispatchToCheckout();
        $this->assertComponentExistsOnPage(self::BLOCK_NAME);
    }

    #[
        ConfigFixture('loki_checkout/general/theme', 'onestep', 'store', 'default'),
        ConfigFixture('loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')
    ]
    public function testDisabledViaService()
    {
        $sameAddressService = $this->createMock(SameAddressService::class);
        $sameAddressService->method('isEnabled')->willReturn(false);
        ObjectManager::getInstance()->addSharedInstance($sameAddressService, SameAddressService::class);

        try {
            $this->dispatchToCheckout();
            $this->assertComponentNotExistsOnPage(self::BLOCK_NAME);
        } finally {
            ObjectManager::getInstance()->removeSharedInstance(SameAddressService::class);
        }
    }
}
