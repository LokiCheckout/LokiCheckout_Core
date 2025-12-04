<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Magento\Framework\DataObject\Copy;
use Magento\Quote\Api\Data\AddressInterface;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Quote\Model\Quote\Address;

class SameAddressService
{
    public function __construct(
        private readonly Config $config,
        private readonly Copy $copyService,
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function isSame(): bool
    {
        if (false === $this->isEnabled()) {
            return false;
        }

        $quote = $this->checkoutState->getQuote();
        if ($quote->isVirtual()) {
            return true;
        }

        return (bool)$quote->getShippingAddress()->getSameAsBilling();
    }

    public function isEnabled(): bool
    {
        return $this->config->enableSameAsBilling();
    }

    public function copyAddressToAddress(Address $address): void
    {
        if ($address->getIsBilling()) {
            $this->copyBillingToShipping();
            return;
        }

        if ($address->getIsShipping()) {
            $this->copyShippingToBilling();
        }
    }

    public function copyBillingToShipping(): void
    {
        if (false === $this->isSame()) {
            return;
        }

        $quote = $this->checkoutState->getQuote();
        $this->copy($quote->getBillingAddress(), $quote->getShippingAddress());
    }

    public function copyShippingToBilling(): void
    {
        if (false === $this->isSame()) {
            return;
        }

        $quote = $this->checkoutState->getQuote();
        $this->copy($quote->getShippingAddress(), $quote->getBillingAddress());
    }

    private function copy(AddressInterface $fromAddress, AddressInterface $toAddress): void
    {
        $this->copyService->copyFieldsetToTarget(
            'customer_address',
            'to_quote_address',
            $fromAddress,
            $toAddress
        );

        if ($fromAddress->getCustomerAddressId()) {
            $toAddress->setCustomerAddressId($fromAddress->getCustomerAddressId());
        }
    }
}

