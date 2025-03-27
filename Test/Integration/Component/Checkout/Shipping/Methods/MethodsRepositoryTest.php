<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\Methods;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertAddressContainsRate;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertAddressHasShippingMethod;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertShippingMethodAvailable;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertTotalsContainShippingMethod;
use Yireo\LokiCheckout\Test\Integration\Trait\SaveShippingAddressValues;
use Magento\TestFramework\Fixture\Config as ConfigFixture;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1]),
    AppArea('frontend'),
]
class MethodsRepositoryTest extends LokiCheckoutPageTestCase
{
    const COMPONENT_NAME = 'loki-checkout.shipping.methods';

    use AssertAddressHasShippingMethod;
    use AssertShippingMethodAvailable;
    use AssertAddressContainsRate;
    use AssertTotalsContainShippingMethod;
    use SaveShippingAddressValues;

    #[
        ConfigFixture('carriers/flatrate/active', 1),
    ]
    public function testGetShippingMethodsWithFlatRate()
    {
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('flatrate_flatrate', $shippingMethods);
    }

    #[
        ConfigFixture('carriers/freeshipping/active', 1, 'store'),
        ConfigFixture('carriers/freeshipping/free_shipping_subtotal', 50),
    ]
    public function testGetShippingMethodsWithFreeShipping()
    {
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('freeshipping_freeshipping', $shippingMethods);
    }


    #[
        ConfigFixture('carriers/instore/active', 1, 'store'),
    ]
    // @todo: Not detected by Shipping::collectRates - is this replaced by MSI?
    public function testGetShippingMethodsWithInStoreShipping()
    {
        $this->markTestSkipped();
        return;
        $this->getCart();
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('instore', $shippingMethods);
    }

    public function testGetShippingMethodsWithTableRatesAndFreeShipping()
    {
        $this->markTestSkipped();
        return;
        $address->setFreeShipping(true);
        $item->setFreeShipping(true);
        $item->setFreeShippingMethod('tablerate_bestway');
    }

    public function testSaveValueWithExistingShippingMethod()
    {
        $this->getCart();
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $repository->saveValue('flatrate_flatrate');

        $quote = $this->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        $this->assertAddressHasShippingMethod('flatrate_flatrate', $shippingAddress);
        $this->assertAddressContainsRate('flatrate', $shippingAddress);
        $this->assertTotalsContainShippingMethod('flatrate_flatrate', $quote);
    }

    public function testSaveValueWithNonExistingShippingMethod()
    {
        $this->getCart();
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $repository->saveValue('foobar_foobar');

        $quote = $this->getQuote();
        $this->assertAddressHasShippingMethod('', $quote->getShippingAddress());
    }

    #[
        ConfigFixture('carriers/flatrate/active', 1),
        ConfigFixture('yireo_loki_checkout/shipping/select_default_shipping_method', 1),
    ]
    public function testSaveValueWithDefaultShippingMethod()
    {
        $this->getCart();
        $this->saveShippingAddressValues();

        $config = ObjectManager::getInstance()->get(Config::class);
        $this->assertTrue($config->selectDefaultShippingMethod());

        $repository = $this->getRepository();
        $repository->saveValue('foobar_foobar');

        $quote = $this->getQuote();
        $this->assertAddressHasShippingMethod('flatrate_flatrate', $quote->getShippingAddress());
    }

    private function getRepository(): ShippingMethodsRepository
    {
        /** @var ShippingMethodsRepository $repository */
        $repository = $this->getComponentRepository(self::COMPONENT_NAME);
        return $repository;
    }
}
