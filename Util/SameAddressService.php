<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Exception\InputException;
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

    public function isEnabled(): bool
    {
        if (false === $this->config->enableSameAsBilling()) {
            return false;
        }

        $quote = $this->checkoutState->getQuote();
        if ($quote->isVirtual()) {
            return false;
        }

        $address = $quote->getShippingAddress();

        return (bool)$address->getSameAsBilling();
    }

    public function copyAddressToAddress(Address $address): void
    {
        if ($address->getIsBilling()) {
            $this->copyBillingToShipping();
        }

        if ($address->getIsShipping()) {
            $this->copyShippingToBilling();
        }
    }

    public function copyBillingToShipping(): void
    {
        if (false === $this->isEnabled()) {
            return;
        }

        // @todo: Detect whether values are the same
        $quote = $this->checkoutState->getQuote();
        $this->copy($quote->getBillingAddress(), $quote->getShippingAddress());
        $this->checkoutState->saveQuote($quote);
    }

    public function copyShippingToBilling(): void
    {
        if (false === $this->isEnabled()) {
            return;
        }

        // @todo: Detect whether values are the same
        $quote = $this->checkoutState->getQuote();
        $this->copy($quote->getShippingAddress(), $quote->getBillingAddress());

        try {
            $this->checkoutState->saveQuote($quote);
        } catch (InputException $inputException) {
            // @todo: What to do here?
        }

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
