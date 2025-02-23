<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\ShippingMethods;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\Collection as TablerateCollection;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\AppIsolation;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Test\Integration\LokiTestCase;
use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Component\ComponentRepositoryInterface;
use Magento\TestFramework\Fixture\Config as ConfigFixture;

#[
    AppIsolation(true),
    DbIsolation(true),
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001', 'price' => 100], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$', 'qty' => 1]),
    AppArea('frontend'),
]
class ShippingMethodsRepositoryTest extends LokiTestCase
{
    const COMPONENT_NAME = 'loki-checkout.shipping.methods';

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[
        ConfigFixture('carriers/flatrate/active', 1),
    ]
    public function testGetShippingMethodsWithFlatRate()
    {
        $this->getCart();
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
        $this->getCart();
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

    #[
        ConfigFixture('carriers/tablerate/active', 1, 'store'),
        ConfigFixture('carriers/tablerate/condition_name', 'package_qty', 'store'),
    ]
    public function testGetShippingMethodsWithTableRates()
    {
        $this->cleanTableRates();
        $this->createTablerate('NL', '*', '*', 0, 1, 'package_qty', '1');

        $this->assertCarrierIsActive('tablerate');

        $this->getCart();
        $this->saveShippingAddressValues();

        $repository = $this->getRepository();
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('tablerate_bestway', $shippingMethods);
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

    private function getQuote(): Quote
    {
        return ObjectManager::getInstance()->get(CheckoutState::class)->getQuote();
    }

    private function getComponentRepository(string $blockId): ComponentRepositoryInterface
    {
        $componentRegistry = ObjectManager::getInstance()->get(ComponentRegistry::class);
        $component = $componentRegistry->getComponentByName($blockId);

        return $component->getRepository();
    }

    private function saveShippingAddressValues(array $values = []): void
    {
        $quote = $this->getQuote();
        $this->assertFalse($quote->isVirtual());

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCustomerEmail($values['email'] ?? 'janedoe@example.org');
        $shippingAddress->setFirstname($values['firstname'] ?? 'Jane');
        $shippingAddress->setLastname($values['lastname'] ?? 'Doe');
        $shippingAddress->setStreet($values['street'] ?? 'Testing Street 12');
        $shippingAddress->setPostcode($values['postcode'] ?? '1111AA');
        $shippingAddress->setCity($values['city'] ?? 'Amsterdam');
        $shippingAddress->setRegion($values['region'] ?? 'Noord-Holland');
        $shippingAddress->setCountryId($values['country_id'] ?? 'NL');

        $this->assertNotEmpty($shippingAddress->getWeight());

        $quote->collectTotals();

        $cartRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
        $cartRepository->save($quote);

        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->setFreeMethodWeight(true);
        $shippingAddress->collectShippingRates();

        $cartRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
        $cartRepository->save($quote);
    }

    /**
     * @params $availableShippingMethods ShippingMethodInterface[]
     */
    private function assertShippingMethodAvailable(string $expectedMethod, array $availableShippingMethods = []): void
    {
        $shippingMethodCodes = [];
        foreach ($availableShippingMethods as $availableShippingMethod) {
            $shippingMethodCodes[] = $availableShippingMethod->getCarrierCode()
                .'_'
                .$availableShippingMethod->getMethodCode();
        }

        $debugMsg = 'Available shipping rates: ';
        $debugMsg .= implode(', ', $shippingMethodCodes);

        $this->assertContains($expectedMethod, $shippingMethodCodes, $debugMsg);
    }

    private function assertCarrierIsActive(string $carrierCode): void
    {
        $shippingConfig = ObjectManager::getInstance()->get(\Magento\Shipping\Model\Config::class);
        $activeCarriers = $shippingConfig->getActiveCarriers();
        $activeCarrierCodes = [];
        foreach ($activeCarriers as $activeCarrier) {
            $activeCarrierCodes[] = $activeCarrier->getCarrierCode();
        }

        $this->assertContains(
            $carrierCode,
            $activeCarrierCodes,
            'Active carriers: '.implode(', ', $activeCarrierCodes)
        );
    }

    private function assertAddressHasShippingMethod(string $expectedMethod, AddressInterface $address): void
    {
        $this->assertSame($expectedMethod, $address->getShippingMethod());
    }

    private function assertAddressContainsRate(string $rateMethod, AddressInterface $address): void
    {
        $found = false;
        $foundRateMethods = [];
        foreach ($address->getShippingRatesCollection() as $shippingRate) {
            /** @var Rate $shippingRate */
            $foundRateMethods[] = $shippingRate->getMethod();
            if ((string)$shippingRate->getMethod() === $rateMethod) {
                $found = true;
                break;
            }
        }

        $debugMsg = 'Rate "'.$rateMethod.'" not found: '.implode(', ', $foundRateMethods);
        $this->assertTrue($found, $debugMsg);
    }

    private function cleanTableRates()
    {
        $resourceConnection = ObjectManager::getInstance()->create(ResourceConnection::class);
        $table = $resourceConnection->getTableName('shipping_tablerate');
        $resourceConnection->getConnection()->query('DELETE FROM '.$table);
    }

    private function createTablerate(
        string $countryId = 'US',
        string $regionId = '*',
        string $zip = '*',
        int $price = 0,
        int $cost = 0,
        string $conditionName = 'package_qty',
        string $conditionValue = '',
        int $websiteId = 1,
    ): void {
        $data = [
            'website_id' => $websiteId,
            'dest_country_id' => $countryId,
            'dest_region_id' => $regionId,
            'dest_zip' => $zip,
            'condition_name' => $conditionName,
            'condition_value' => $conditionValue,
            'price' => $price,
            'cost' => $cost,
        ];

        $resourceModel = ObjectManager::getInstance()->create(Tablerate::class);
        $resourceModel->getConnection()->insertArray(
            $resourceModel->getMainTable(),
            array_keys($data),
            [$data]
        );

        $tablerateCollection = ObjectManager::getInstance()->create(TablerateCollection::class);
        $this->assertNotEmpty($tablerateCollection->count());
    }

    private function assertConfigValue(
        mixed $expectedValue,
        string $path,
        string $scopeType = 'default',
        mixed $scopeValue = null
    ): void {
        $scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $value = $scopeConfig->getValue($path, $scopeType, $scopeValue);
        $this->assertSame($expectedValue, $value);
    }

    private function assertConfigFlag(
        bool $expectedValue,
        string $path,
        string $scopeType = 'default',
        mixed $scopeValue = null
    ): void {
        $scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $value = $scopeConfig->isSetFlag($path, $scopeType, $scopeValue);
        $this->assertSame($expectedValue, $value);
    }

    private function getRepository(): ShippingMethodsRepository
    {
        return $this->getComponentRepository(self::COMPONENT_NAME);
    }

    private function assertTotalsContainShippingMethod(string $shippingMethod, Quote $quote): void
    {
        $totals = $quote->getTotals();
        $totalCodes = [];
        foreach ($totals as $total) {
            $totalCodes[] = $total->getCode();
            $totalStrings[] = var_export($total->getFullInfo(), true);
        }

        $this->assertNotEmpty($totalCodes);
        $this->assertContains($shippingMethod, $totalStrings, var_export($totalStrings, true));
    }
}
