<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Magento\Quote\Model\Quote;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressViewModel extends ComponentViewModel
{
    public function getAddresses(): array
    {
        if (false === $this->getContext()->getCustomerSession()->isLoggedIn()) {
            return [];
        }

        return $this->getContext()->getCustomerProvider()->getAddresses();
    }

    public function isNewAddress(): bool
    {
        return $this->getContext()->isNewAddress();
    }

    public function isEnabled(): bool
    {
        return false === (bool)$this->getQuote()->isVirtual();
    }

    protected function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutSession()->getQuote();
    }
}
