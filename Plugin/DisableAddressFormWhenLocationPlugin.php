<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use LokiCheckout\Core\Component\Checkout\Address\AddressFormViewModel;
use LokiCheckout\Core\Shipment\ShippingAddressResolverListing;

class DisableAddressFormWhenLocationPlugin
{
    public function __construct(
        private readonly ShippingAddressResolverListing $shippingAddressResolverListing,
    ) {
    }

    public function afterShowView(AddressFormViewModel $subject, bool $result): bool
    {
        if (false === $subject->isShippingAddressComponent()) {
            return $result;
        }

        if (false === $this->shippingAddressResolverListing->resolves()) {
            return $result;
        }

        if (false === (bool)$subject->getBlock()->getCardLayout()) {
            return $result;
        }

        return true;
    }
}
