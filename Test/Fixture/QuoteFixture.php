<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\ItemFactory;
use Magento\TestFramework\Fixture\RevertibleDataFixtureInterface;

class QuoteFixture implements RevertibleDataFixtureInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ProductFactory $productFactory,
        private QuoteFactory $quoteFactory,
        private ItemFactory $quoteItemFactory,
        private CartRepositoryInterface $cartRepository,
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        $product = $this->productFactory->create();
        $product->setSku('test01');
        $product->setName('Test Product 01');
        $product->setTypeId('simple');
        $product->setAttributeSetId(1);
        $this->productRepository->save($product);

        $quoteItem = $this->quoteItemFactory->create();
        $quoteItem->setPrice(42.0);
        $quoteItem->setQty(1);

        $quote = $this->quoteFactory->create($data);
        $quote->setStoreId(1);
        $quote->setItems([$quoteItem]);
        $quote->setItemsCount(1);

        $this->cartRepository->save($quote);
        return $quote;
    }

    public function revert(DataObject $data): void
    {
    }
}
