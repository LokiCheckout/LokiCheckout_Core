<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\DataObject\Copy;
use Magento\Quote\Api\Data\AddressInterface;
use Yireo\LokiCheckout\Config\Config;

class BillingAsShippingService
{
    public function __construct(
        private readonly Config $config,
        private readonly Copy $copyService,
    ) {
    }

    public function getValueFromAddress(AddressInterface $address): bool
    {
        if (false === $this->config->enableSameAsBilling()) {
            return false;
        }

        $currentValue = $address->getSameAsBilling();

        if ($currentValue !== null) {
            return (bool)$currentValue;
        }

        return $this->config->getSameAsBillingValue();
    }

    public function copy(AddressInterface $fromAddress, AddressInterface $toAddress): void
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
