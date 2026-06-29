<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use LokiCheckout\Core\Component\Checkout\Billing\ShippingAsBilling\ShippingAsBillingViewModel;
use LokiCheckout\Core\Shipment\ShippingAddressResolverListing;

class HideSameAsBillingForLocationPlugin
{
    public function __construct(
        private readonly ShippingAddressResolverListing $shippingAddressResolverListing,
    ) {
    }

    public function afterIsAllowRendering(
        ShippingAsBillingViewModel $shippingAsBillingViewModel,
        bool $result
    ): bool {
        if ($this->shippingAddressResolverListing->resolves()) {
            return false;
        }

        return $result;
    }
}
