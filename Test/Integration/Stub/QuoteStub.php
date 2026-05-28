<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Stub;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Payment\Model\Cart;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartExtensionInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CurrencyInterface;
use Magento\Quote\Api\Data\ProductOptionInterface;

class QuoteStub implements CartInterface
{
    public function __construct(
        private int $id = 0,
        private string $createdAt = '',
        private string $updatedAt = '',
        private string $convertedAt = '',
        private bool $isActive = true,
        private bool $isVirtual = false,
        private bool $customerIsGuest = true,
        private string $customerNote = '',
        private array $items = [],
        private ?CustomerInterface $customer = null,
        private ?AddressInterface $billingAddress = null,
        private ?AddressInterface $shippingAddress = null,
        private int $reserverOrderId = 0,
        private ?CurrencyInterface $currency = null,
        private int $origOrderId = 0,
        private bool $customerNoteNotify = false,
        private int $customerTaxClassId = 1,
        private int $storeId = 1,
        private ?CartExtensionInterface $extensionAttributes = null
    ) {
    }

    public function getItemsCount()
    {
        return count($this->items);
    }

    public function setItemsCount($itemsCount)
    {
    }

    public function getItemsQty()
    {
        return count($this->items);
    }

    public function setItemsQty($itemQty)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = (int)$id;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = (string)$createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = (string)$updatedAt;
    }

    public function getConvertedAt(): string
    {
        return $this->convertedAt;
    }

    public function setConvertedAt($convertedAt): void
    {
        $this->convertedAt = (string)$convertedAt;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive($isActive): void
    {
        $this->isActive = (bool)$isActive;
    }

    public function isVirtual(): bool
    {
        return $this->isVirtual;
    }

    public function setIsVirtual($isVirtual): void
    {
        $this->isVirtual = $isVirtual;
    }

    public function isCustomerIsGuest(): bool
    {
        return $this->customerIsGuest;
    }

    public function setCustomerIsGuest($customerIsGuest): void
    {
        $this->customerIsGuest = (bool)$customerIsGuest;
    }

    public function getCustomerNote(): string
    {
        return $this->customerNote;
    }

    public function setCustomerNote($customerNote): void
    {
        $this->customerNote = (string)$customerNote;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(?array $items = null): void
    {
        if (is_array($items)) {
            $this->items = $items;
        }
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(CustomerInterface|null $customer = null): void
    {
        $this->customer = $customer;
    }


    public function getShippingAddress(): ?AddressInterface
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(AddressInterface|null $shippingAddress = null): void
    {
        $this->shippingAddress = $shippingAddress;
    }


    public function getBillingAddress(): ?AddressInterface
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(AddressInterface|null $billingAddress = null): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getReserverOrderId(): int
    {
        return $this->reserverOrderId;
    }

    public function setReserverOrderId(int $reserverOrderId): void
    {
        $this->reserverOrderId = $reserverOrderId;
    }

    public function getCurrency(): ?CurrencyInterface
    {
        return $this->currency;
    }

    public function setCurrency(CurrencyInterface|null $currency = null): void
    {
        $this->currency = $currency;
    }

    public function getOrigOrderId(): int
    {
        return $this->origOrderId;
    }

    public function setOrigOrderId($origOrderId): void
    {
        $this->origOrderId = (int)$origOrderId;
    }

    public function isCustomerNoteNotify(): bool
    {
        return $this->customerNoteNotify;
    }

    public function setCustomerNoteNotify($customerNoteNotify): void
    {
        $this->customerNoteNotify = (bool)$customerNoteNotify;
    }

    public function getCustomerTaxClassId(): int
    {
        return $this->customerTaxClassId;
    }

    public function setCustomerTaxClassId($customerTaxClassId): void
    {
        $this->customerTaxClassId = (int)$customerTaxClassId;
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function setStoreId($storeId): void
    {
        $this->storeId = (int)$storeId;
    }

    public function getExtensionAttributes(): ?CartExtensionInterface
    {
        return $this->extensionAttributes;
    }

    public function setExtensionAttributes(?CartExtensionInterface $extensionAttributes): void
    {
        $this->extensionAttributes = $extensionAttributes;
    }
}
