<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

trait ProductFixture
{
    use GetObjectManager;

    public function getProduct(string $productType = Type::TYPE_SIMPLE): ProductInterface
    {
        $productId = 1;
        if ($productType === Type::TYPE_VIRTUAL) {
            $productId = 2;
        }

        try {
            $product = $this->getProductRepository()->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = $this->createProduct($productId, $productType);
        }

        $product->setData('salable', true);

        /** @var Product $product */
        $this->assertTrue($product->getId() > 0, 'No product ID given');
        $this->assertEquals($productType, $product->getTypeId(), 'Wrong product type');
        $this->assertTrue($product->isSalable(), 'Product is not salable');

        return $product;
    }

    public function createProduct($productId = 1, string $productType = Type::TYPE_SIMPLE): ProductInterface
    {
        $productSku = $productType.'-product-'.$productId;
        $product = $this->getObjectManager()->create(ProductInterface::class);

        if ($productId > 0) {
            $product->setId($productId);
        }

        $product->addData([
            'type_id' => $productType,
            'attribute_set_id' => 4,
            'name' => ucfirst($productType).' Product '.$productId,
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
        $stockItem = $stockRegistry->getStockItemBySku($product->getSku(), 1);
        $stockItem->setUseConfigManageStock(0);
        $stockItem->setManageStock(1);
        $stockItem->setIsInStock(1);
        $stockItem->setIsQtyDecimal(0);
        $stockItem->setProductId($product->getId());
        $stockItem->setQty(9999);
        $stockItem->save();

        $this->getProductRepository()->save($product); // @todo: Is this needed?

        return $this->getProductRepository()->get($productSku);
    }

    private function getProductRepository(): ProductRepositoryInterface
    {
        return $this->getObjectManager()->get(ProductRepositoryInterface::class);
    }
}
