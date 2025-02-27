<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddressSelect;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressSelectViewModel extends CheckoutViewModel
{
    public function getAddressLines(): array
    {
        return $this->getContext()->getCustomerProvider()->getAddressLines();
    }

    public function getFieldLabel(): string
    {
        return 'Select your Shipping Address';
    }

    public function isDisabled(): bool
    {
        return (bool)$this->getContext()->isNewAddress();
    }

    public function isAllowRendering(): bool
    {
        if (false === $this->getContext()->getCustomerProvider()->isLoggedIn()) {
            return false;
        }

        return parent::isAllowRendering();
    }
}
