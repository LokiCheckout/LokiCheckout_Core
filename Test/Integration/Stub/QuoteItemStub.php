<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Stub;

use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\ProductOptionInterface;

class QuoteItemStub implements CartItemInterface
{
    public function __construct(
        private int $itemId = 1,
        private string $sku = '',
        private int $qty = 1,
        private string $name = 'Product Name ',
        private float $price = 1.00,
        private string $productType = 'simple',
        private int $quoteId = 1,
        private ?ProductOptionInterface $productOption = null,
        private ?CartItemExtensionInterface $extensionAttributes = null,
    ) {
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function setItemId($itemId): void
    {
        $this->itemId = (int)$itemId;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku($sku): void
    {
        $this->sku = (string)$sku;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function setQty($qty): void
    {
        $this->qty = (int)$qty;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = (string)$name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice($price): void
    {
        $this->price = (float)$price;
    }

    public function getProductType(): string
    {
        return $this->productType;
    }

    public function setProductType($productType): void
    {
        $this->productType = (string)$productType;
    }

    public function getQuoteId(): int
    {
        return $this->quoteId;
    }

    public function setQuoteId($quoteId): void
    {
        $this->quoteId = (int)$quoteId;
    }

    public function getProductOption(): ?ProductOptionInterface
    {
        return $this->productOption;
    }

    public function setProductOption(?ProductOptionInterface $productOption): void
    {
        $this->productOption = $productOption;
    }

    public function getExtensionAttributes(): ?CartItemExtensionInterface
    {
        return $this->extensionAttributes;
    }

    public function setExtensionAttributes(?CartItemExtensionInterface $extensionAttributes): void
    {
        $this->extensionAttributes = $extensionAttributes;
    }
}
