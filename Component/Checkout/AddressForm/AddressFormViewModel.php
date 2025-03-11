<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\AddressForm;

use Magento\Quote\Model\Quote;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

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

        if ($this->getContext()->isShippingAddressComponent($this->getComponent())) {
            if ($this->getQuote()->isVirtual()) {
                return false;
            }
        }

        return true;
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
        return $this->getContext()->getSameAddressService()->isEnabled();
    }

    public function isNewAddress(): bool
    {
        return $this->getContext()->isNewAddress($this->getComponent());
    }

    public function showSelectAddress(): bool
    {
        return count($this->getAddresses()) > 0;
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

