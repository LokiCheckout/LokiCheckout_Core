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
use Magento\Framework\Exception\NoSuchEntityException;
use Yireo\LokiCheckout\Test\Integration\Stub\FulltextStub;

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

        /** @var Product $product */
        $this->assertTrue($product->getId() > 0, 'No product ID given');
        $this->assertTrue($product->isSalable(), 'Product is not salable');

        return $product;
    }

    public function createProduct($productId = 0): ProductInterface
    {
        $this->getObjectManager()->configure([
            'preferences' => [
                Fulltext::class => FulltextStub::class
            ]
        ]);

        $productSku = 'simple-product-1';
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
        $this->getProductRepository()->save($product);

        $stockRegistry = $this->getObjectManager()->get(StockRegistryInterface::class);
        $stockItem = $stockRegistry->getStockItemBySku($product->getSku());
        $stockItem->setUseConfigManageStock(1);
        $stockItem->setIsInStock(1);
        $stockItem->setProductId($product->getId());
        $stockItem->setQty(9999);
        $stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);

        return $this->getProductRepository()->get($productSku);
    }

    private function getProductRepository(): ProductRepositoryInterface
    {
        return $this->getObjectManager()->get(ProductRepositoryInterface::class);
    }
}
