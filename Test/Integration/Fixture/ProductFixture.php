<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogSearch\Model\Indexer\Fulltext;
use Magento\CatalogSearch\Model\Indexer\Fulltext\Plugin\Product as FulltextProductPlugin;
use Magento\Framework\App\Cache;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;
use Yireo\LokiCheckout\Test\Integration\Stub\FulltextProductPluginStub;
use Yireo\LokiCheckout\Test\Integration\Stub\FulltextStub;

trait ProductFixture
{
    use GetObjectManager;

    public function getProduct($productId = 0): ProductInterface
    {
        $configWriter = $this->getObjectManager()->get(\Magento\Framework\App\Config\Storage\WriterInterface::class);
        $configWriter->save('cataloginventory/item_options/min_qty', -1);
        $cache = $this->getObjectManager()->get(Cache::class);
        $cache->clean(['config']);

        try {
            $product = $this->getProductRepository()->getById($productId);

        } catch (NoSuchEntityException $e) {
            $product = $this->createProduct($productId);
        }

        $product->setData('salable', true);

        /** @var Product $product */
        $this->assertTrue($product->getId() > 0, 'No product ID given');
        $this->assertTrue($product->isSalable(), 'Product is not salable');

        return $product;
    }

    public function createProduct($productId = 0): ProductInterface
    {
        /*
        $this->getObjectManager()->configure([
            'preferences' => [
                Fulltext::class => FulltextStub::class,
                FulltextProductPlugin::class => FulltextProductPluginStub::class
            ]
        ]);*/

        // Hack: Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException : URL key for specified store already exists.
        $resourceConnection = $this->getObjectManager()->get(ResourceConnection::class);
        $resourceConnection->getConnection()->query('DELETE FROM url_rewrite WHERE entity_type="product"');

        $productSku = 'simple-product-'.rand(1,1000);
        $product = $this->getObjectManager()->create(ProductInterface::class);

        if ($productId > 0) {
            $product->setId($productId);
        }

        $product->addData([
            'type_id' => Type::TYPE_SIMPLE,
            'attribute_set_id' => 4,
            'name' => 'Simple Product 1',
            'sku' => $productSku,
            'price' => 10,
            'weight' => 1,
            'visibility' => Visibility::VISIBILITY_BOTH,
            'status' => Status::STATUS_ENABLED,
        ]);

        $product->setCustomAttribute('tax_class_id', '2');
        $product->getExtensionAttributes()->setWebsiteIds([1]);
        $product->isObjectNew(true);
        $product->setIsSuperMode(true);

        $this->getProductRepository()->save($product);

        $stockRegistry = $this->getObjectManager()->get(StockRegistryInterface::class);
        $stockItem = $stockRegistry->getStockItemBySku($product->getSku());
        $stockItem->setUseConfigManageStock(0);
        $stockItem->setManageStock(1);
        $stockItem->setIsInStock(1);
        $stockItem->setIsQtyDecimal(0);
        $stockItem->setProductId($product->getId());
        $stockItem->setQty(9999);
        $stockItem->save();
        //$stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);

        $this->getProductRepository()->save($product);

        return $this->getProductRepository()->get($productSku);
    }

    private function getProductRepository(): ProductRepositoryInterface
    {
        return $this->getObjectManager()->get(ProductRepositoryInterface::class);
    }
}
