<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddressToggleSelect;

use Yireo\LokiCheckout\Component\Checkout\Shipping\ShippingAddress\ShippingAddressContext;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method ShippingAddressContext getContext()
 * @method ShippingAddressToggleSelectRepository getRepository()
 */
class ShippingAddressToggleSelectViewModel extends ComponentViewModel
{
    public function isNewAddress(): bool
    {
        return (bool)$this->getRepository()->get();
    }

    public function getAlpineData(): array
    {
        return [
            'newAddress' => $this->getContext()->isNewAddress() ? true : false,
            'buttonLabelSelectAddress' => $this->getButtonLabelSelectAddress(),
            'buttonLabelNewAddress' => $this->getButtonLabelNewAddress(),
        ];
    }

    public function getButtonLabelNewAddress(): string
    {
        return (string)__('Add new shipping address');
    }

    public function getButtonLabelSelectAddress(): string
    {
        return (string)__('Select existing address from list');
    }
}
