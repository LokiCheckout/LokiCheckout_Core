<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Exception\InputException;
use Magento\Quote\Api\Data\AddressInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

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
