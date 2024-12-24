<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddressSelect;

use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;
use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;

/**
 * @method ShippingAddressContext getContext()
 */
class ShippingAddressSelectViewModel extends GenericViewModel
{
    public function getAddresses(): array
    {
        return $this->getContext()->customerProvider->getAddresses();
    }

    public function getCurrentAddressId(): int
    {
        return (int)$this->getData();
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
        if (false === $this->getContext()->customerProvider->isLoggedIn()) {
            return false;
        }

        return parent::isAllowRendering();
    }
}
