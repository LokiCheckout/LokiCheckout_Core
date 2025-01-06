<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Yireo\LokiCheckout\Component\Checkout\Address\AddressContext;

class ShippingAddressContext extends AddressContext
{
    public function isNewAddress(): bool
    {
        if (false === $this->getCustomerSession()->isLoggedIn()) {
            return true;
        }

        return (bool)$this->getCheckoutSession()->getIsNewShippingAddress();
    }
}
