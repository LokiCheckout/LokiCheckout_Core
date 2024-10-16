<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock;
use Magento\Framework\Exception\NoSuchEntityException;

trait ProductFixture
{
    use GetObjectManager;

    public function getProduct($productId = 0): ProductInterface
    {
        try {
            $product = $this->getProductRepository()->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = $this->createProduct($productId);
        }

        $this->assertTrue($product->getId() > 0);

        $stockStatus = $this->getObjectManager()->get(\Magento\CatalogInventory\Model\ResourceModel\Stock\Status::class);
        $stockStatus->saveProductStatus($product->getId(), Stock::STOCK_IN_STOCK, 100, 1);

        $stockRegistry = $this->getObjectManager()->get(StockRegistryInterface::class);
        $stockStatus = $stockRegistry->getStockStatus($product->getId(), 1);
        $this->assertTrue($stockStatus->getStockStatus() === Stock::STOCK_IN_STOCK);

        return $product;
    }

    public function createProduct($productId = 0): ProductInterface
    {
        $product = $this->getObjectManager()->create(ProductInterface::class);

        if ($productId > 0) {
            $product->setId($productId);
        }

        $product->addData([
            'type_id' => Type::TYPE_SIMPLE,
            'attribute_set_id' => 4,
            'name' => 'Simple Product 1',
            'sku' => 'simple-product-1',
            'price' => 10,
            'weight' => 1,
            'visibility' => Visibility::VISIBILITY_BOTH,
            'status' => Status::STATUS_ENABLED,
        ]);

        $product->setCustomAttribute('tax_class_id', '2');
        $product->getExtensionAttributes()->setWebsiteIds([1]);
        $stockItem = $this->getObjectManager()->create(StockItemInterface::class);

        $product->getExtensionAttributes()->setStockItem($stockItem);
        $this->getProductRepository()->save($product);

        $stockRegistry = $this->getObjectManager()->get(StockRegistryInterface::class);
        $stockItem = $stockRegistry->getStockItem($product->getId(), 1);
        $stockItem->setUseConfigManageStock(false);
        $stockItem->setQty(100);
        $stockItem->setIsInStock(true);
        $stockItem->save();

        return $product;
    }

    private function getProductRepository(): ProductRepositoryInterface
    {
        return $this->getObjectManager()->get(ProductRepositoryInterface::class);
    }
}
