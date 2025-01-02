<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Checkout\Address\AddressContext;

class BillingAddressContext extends AddressContext
{
    public function isNewAddress(): bool
    {
        if (false === $this->getCustomerSession()->isLoggedIn()) {
            return true;
        }

        return (bool)$this->getCustomerSession()->getIsNewBillingAddress();
    }
}
