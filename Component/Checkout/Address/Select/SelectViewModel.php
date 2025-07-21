<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Select;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;

/**
 * @method AddressFormContext getContext()
 */
class SelectViewModel extends CheckoutViewModel
{
    public function getAddressLines(): array
    {
        return $this->getContext()->getCustomerProvider()->getAddressLines();
    }

    public function getFieldLabel(): string
    {
        if ($this->getContext()->isBillingAddressComponent($this->getComponent())) {
            return 'Select your Billing Address';
        }

        return 'Select your Shipping Address';
    }

    public function isDisabled(): bool
    {
        return (bool)$this->getContext()->isNewAddress($this->getComponent());
    }

    public function isAllowRendering(): bool
    {
        if (false === $this->getContext()->getCustomerProvider()->isLoggedIn()) {
            return false;
        }

        return parent::isAllowRendering();
    }
}
