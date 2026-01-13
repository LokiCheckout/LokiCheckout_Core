<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData\Checkout;

use Magento\Quote\Api\Data\CartItemInterface;

class CartItemDataCollector
{
    public function getData(CartItemInterface $item): array
    {
        return [
            'item_id' => $item->getItemId(),
            'name' => $item->getName(),
            'price' => $item->getPrice(),
            'qty' => $item->getQty(),
            'sku' => $item->getSku(),
            'product_type' => $item->getProductType(),
        ];
    }
}
