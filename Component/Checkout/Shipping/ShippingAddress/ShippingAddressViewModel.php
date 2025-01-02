<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Magento\Quote\Model\Quote;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressViewModel extends ComponentViewModel
{
    public function isEnabled(): bool
    {
        return false === (bool)$this->getQuote()->isVirtual();
    }

    public function isSameAddress(): bool
    {
        return $this->getContext()->getSameAddressService()->isEnabled();
    }

    public function isNewAddress(): bool
    {
        return $this->getContext()->isNewAddress();
    }

    public function getAddresses(): array
    {
        if (false === $this->getContext()->getCustomerSession()->isLoggedIn()) {
            return [];
        }

        return $this->getContext()->getCustomerProvider()->getAddresses();
    }

    protected function getQuote(): Quote
    {
        return $this->getContext()->getQuote();
    }
}
