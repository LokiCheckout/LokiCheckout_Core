<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address;

use Magento\Quote\Model\Quote;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

/**
 * @method AddressFormContext getContext()
 */
class AddressFormViewModel extends CheckoutViewModel
{
    // @todo: Should this not be isAllowedRendering?
    public function isEnabled(): bool
    {
        if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
            return $this->getContext()->getCheckoutState()->getQuote()->getGrandTotal() > 0;
            // @todo: Check how this goes
        }
        return !($this->getContext()->isShippingAddressComponent($this->getComponent()) && $this->getQuote()->isVirtual());
    }

    public function isAllowRendering(): bool
    {
        if ($this->getQuote()->isVirtual() && $this->getContext()->isShippingAddressComponent($this->getComponent())) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function isSameAddress(): bool
    {
        $address = $this->getContext()->getCurrentAddress($this->getComponent());
        return $this->getContext()->getSameAddressService()->isSame($address);
    }

    public function isNewAddress(): bool
    {
        return $this->getContext()->isNewAddress($this->getComponent());
    }

    public function showSelectAddress(): bool
    {
        return $this->getAddresses() !== [];
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
        $address = $this->getContext()->getCurrentAddress($this->getComponent());

        return $this->getContext()->getAddressRenderer()->getHtml($address);
    }

    private function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutState()->getQuote();
    }
}
