<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Shipping\Methods;

use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use LokiCheckout\Core\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Test\Integration\Trait\AssertAddressContainsRate;
use LokiCheckout\Core\Test\Integration\Trait\AssertAddressHasShippingMethod;
use LokiCheckout\Core\Test\Integration\Trait\AssertShippingMethodAvailable;
use LokiCheckout\Core\Test\Integration\Trait\AssertTotalsContainShippingMethod;
use LokiCheckout\Core\Test\Integration\Trait\SaveShippingAddressValues;

#[
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class MethodsRepositoryTest extends LokiCheckoutTestCase
{
    const COMPONENT_NAME = 'loki-checkout.shipping.methods';

    use AssertAddressHasShippingMethod;
    use AssertShippingMethodAvailable;
    use AssertAddressContainsRate;
    use AssertTotalsContainShippingMethod;
    use SaveShippingAddressValues;

    #[
        ConfigFixture('carriers/flatrate/active', 1, 'store', 'default'),
    ]
    public function testGetShippingMethodsWithFlatRate()
    {
        $this->dispatchToCheckout();
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('flatrate_flatrate', $shippingMethods);
    }

    #[
        DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 0], as: 'product'),
        DataFixture(GuestCartFixture::class, as: 'cart'),
        DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
        ConfigFixture('carriers/freeshipping/active', 1, 'store', 'default'),
        ConfigFixture('carriers/freeshipping/free_shipping_subtotal', 0, 'store', 'default'),
    ]
    public function testGetShippingMethodsWithFreeShipping()
    {
        $this->dispatchToCheckout();
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('freeshipping_freeshipping', $shippingMethods);
    }

    #[
        ConfigFixture('carriers/instore/active', 1, 'store', 'default'),
    ]
    // @todo: Not detected by Shipping::collectRates - is this replaced by MSI?
    public function testGetShippingMethodsWithInStoreShipping()
    {
        $this->markTestSkipped();
        $this->dispatchToCheckout();
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('instore', $shippingMethods);
    }

    public function testGetShippingMethodsWithTableRatesAndFreeShipping()
    {
        $this->markTestSkipped();
    }

    public function testSaveValueWithExistingShippingMethod()
    {
        $this->saveShippingAddressValues();
        $this->dispatchToCheckout();

        $repository = $this->getRepository();
        $repository->saveValue('flatrate_flatrate');

        $quote = $this->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        $this->assertAddressHasShippingMethod('flatrate_flatrate', $shippingAddress);
        $this->assertAddressContainsRate('flatrate', $shippingAddress);
    }

    public function testSaveValueWithNonExistingShippingMethod()
    {
        $this->saveShippingAddressValues();
        $this->dispatchToCheckout();

        $repository = $this->getRepository();
        $repository->saveValue('foobar_foobar');

        $quote = $this->getQuote();
        $this->assertAddressHasShippingMethod('', $quote->getShippingAddress());
    }

    #[
        ConfigFixture('carriers/flatrate/active', 1),
        ConfigFixture('loki_checkout/shipping/select_default_shipping_method', 1, 'store', 'default'),
    ]
    public function testSaveValueWithDefaultShippingMethod()
    {
        $this->saveShippingAddressValues();
        $this->dispatchToCheckout();

        $config = ObjectManager::getInstance()->get(Config::class);
        $this->assertTrue($config->selectDefaultShippingMethod());

        $repository = $this->getRepository();
        $repository->saveValue('foobar_foobar');

        $quote = $this->getQuote();
        $this->assertAddressHasShippingMethod('flatrate_flatrate', $quote->getShippingAddress());
    }

    #[
        ConfigFixture('carriers/flatrate/active', 1, 'store', 'default'),
        ConfigFixture('carriers/flatrate/sort_order', 20, 'store', 'default'),
        ConfigFixture('carriers/freeshipping/active', 1, 'store', 'default'),
        ConfigFixture('carriers/freeshipping/sort_order', 10, 'store', 'default'),
        ConfigFixture('carriers/freeshipping/showmethod', 1, 'store', 'default'),
        ConfigFixture('carriers/freeshipping/free_shipping_subtotal', 100000, 'store', 'default'),
        ConfigFixture('loki_checkout/shipping/select_default_shipping_method', 1, 'store', 'default'),
    ]
    public function testUnavailableShippingMethodIsNeverSelected()
    {
        $this->saveShippingAddressValues();
        $this->dispatchToCheckout();

        $repository = $this->getRepository();
        $unavailableCodes = [];
        foreach ($repository->getShippingMethods() as $code => $shippingMethod) {
            if (false === $shippingMethod->getAvailable()) {
                $unavailableCodes[] = $code;
            }
        }

        $this->assertNotEmpty($unavailableCodes, 'Expected an unavailable (error) rate for this test');

        $repository->saveValue('');
        $this->assertAddressHasShippingMethod('flatrate_flatrate', $this->getQuote()->getShippingAddress());

        $repository->saveValue($unavailableCodes[0]);
        $this->assertAddressHasShippingMethod('flatrate_flatrate', $this->getQuote()->getShippingAddress());
    }

    private function getRepository(): ShippingMethodsRepository
    {
        /** @var ShippingMethodsRepository $repository */
        $repository = $this->getComponentRepository(self::COMPONENT_NAME);
        return $repository;
    }
}
