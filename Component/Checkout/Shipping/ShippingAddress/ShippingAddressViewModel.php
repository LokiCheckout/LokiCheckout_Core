<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress;

use Magento\Quote\Model\Quote;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressViewModel extends CheckoutViewModel
{
    public function isAllowRendering(): bool
    {
        if ($this->getQuote()->isVirtual()) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function isEnabled(): bool
    {
        return false === (bool)$this->getQuote()->isVirtual();
    }

    public function isSameAddress(): bool
    {
        return $this->getContext()->getSameAddressService()->isEnabled();
    }

    public function showSelectAddress(): bool
    {
        return count($this->getAddresses()) > 0;
    }

    public function isNewAddress(): bool
    {
        if (false === count($this->getAddresses()) > 0) {
            return true;
        }

        return $this->getContext()->isNewAddress();
    }

    public function getAddresses(): array
    {
        if (false === $this->getContext()->getCustomerSession()->isLoggedIn()) {
            return [];
        }

        return $this->getContext()->getCustomerProvider()->getAddressLines();
    }

    public function getHtml(): string
    {
        $address = $this->getContext()->getShippingAddressState()->getShippingAddress();
        return $this->getContext()->getAddressRenderer()->getHtml($address);
    }

    private function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutState()->getQuote();
    }
}
