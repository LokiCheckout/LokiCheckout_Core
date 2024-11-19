<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\DataObject\Copy;
use Magento\Quote\Api\Data\AddressInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;

class BillingAsShippingService
{
    public function __construct(
        private readonly Config $config,
        private readonly StepNavigator $stepNavigator,
        private readonly Copy $copyService,
    ) {
    }

    public function isEnabled(): bool
    {
        if (false === $this->config->enableSameAsBilling()) {
            return false;
        }

        try {
            $this->stepNavigator->getStepByCode('shipping');
        } catch(UnknownStepException) {
            return count($this->stepNavigator->getSteps()) === 1;
        }

        return true;
    }

    public function getValueFromAddress(AddressInterface $address)
    {
        if (false === $this->isEnabled()) {
            return false;
        }

        return (bool)$address->getSameAsBilling();
    }

    public function copy(AddressInterface $fromAddress, AddressInterface $toAddress)
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
