<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use LokiCheckout\Core\Shipment\ShippingAddressResolverListing;
use LokiCheckout\Core\Util\SameAddressService;

class DisableSameAsBillingForLocationPlugin
{
    public function __construct(
        private readonly ShippingAddressResolverListing $shippingAddressResolverListing,
    ) {
    }

    public function afterIsSame(SameAddressService $service, bool $result): bool
    {
        if ($this->shippingAddressResolverListing->resolves()) {
            return false;
        }

        return $result;
    }
}
