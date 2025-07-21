<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Cart;

use Magento\Quote\Model\Quote\Item;

class ItemConvertor
{
    public function toArray(Item $item): array
    {
        return [
            'id' => $item->getId(),
            'sku' => $item->getSku(),
            'name' => $item->getName(),
            'price_incl_tax' => $item->getPriceInclTax(),
            'price' => $item->getPrice(),
            'qty' => $item->getQty(),
            'additional_data' => $item->getAdditionalData(),
        ];
    }
}
