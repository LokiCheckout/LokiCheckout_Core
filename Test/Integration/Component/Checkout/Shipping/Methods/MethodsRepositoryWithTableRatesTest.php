<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Shipping\Methods;

use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\Collection as TablerateCollection;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingMethods\ShippingMethodsRepository;
use Yireo\LokiCheckout\Test\Integration\LokiCheckoutPageTestCase;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertAddressContainsRate;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertAddressHasShippingMethod;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertCarrierIsActive;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertShippingMethodAvailable;
use Yireo\LokiCheckout\Test\Integration\Trait\AssertTotalsContainShippingMethod;
use Yireo\LokiCheckout\Test\Integration\Trait\SaveShippingAddressValues;

#[
    DataFixture(ProductFixture::class, ['sku' => 'simple-product-001'], as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class MethodsRepositoryWithTableRatesTest extends LokiCheckoutPageTestCase
{
    protected ?bool $skipDispatchToCheckout = true;

    const COMPONENT_NAME = 'loki-checkout.shipping.methods';

    use AssertAddressHasShippingMethod;
    use AssertShippingMethodAvailable;
    use AssertAddressContainsRate;
    use AssertTotalsContainShippingMethod;
    use AssertCarrierIsActive;
    use SaveShippingAddressValues;

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

        $this->dispatchToCheckout();

        /** @var ShippingMethodsRepository $repository */
        $repository = $this->getComponentRepository(self::COMPONENT_NAME);
        $shippingMethods = $repository->getShippingMethods();
        $this->assertShippingMethodAvailable('tablerate_bestway', $shippingMethods);
    }

    private function cleanTableRates()
    {
        $resourceConnection = ObjectManager::getInstance()->create(ResourceConnection::class);
        $table = $resourceConnection->getTableName('shipping_tablerate');
        $resourceConnection->getConnection()->query('DELETE FROM ' . $table);
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
}
