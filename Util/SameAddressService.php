<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Magento\Framework\DataObject\Copy;
use Magento\Quote\Api\Data\AddressInterface;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\ViewModel\CheckoutState;

class SameAddressService
{
    public function __construct(
        private readonly Config $config,
        private readonly Copy $copyService,
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function isShippingFirst(): bool
    {
        return true;
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

    public function copyAddressToAddress(): void
    {
        if (false === $this->isShippingFirst()) {
            $this->copyBillingToShipping();
            return;
        }

        $quote = $this->checkoutState->getQuote();
        if ($quote->isVirtual()) {
            $this->copyBillingToShipping();
            return;
        }

        $this->copyShippingToBilling();
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
        $this->checkoutState->saveQuote($quote);
    }

    private function copy(AddressInterface $fromAddress, AddressInterface $toAddress): void
    {
        $this->copyService->copyFieldsetToTarget(
            'customer_address',
            'to_quote_address',
            $fromAddress,
            $toAddress
        );

        if ($fromAddress->getCustomerAddressId() > 0) {
            $toAddress->setCustomerAddressId($fromAddress->getCustomerAddressId());
        } else {
            $toAddress->setCustomerAddressId(null);
        }
    }
}

