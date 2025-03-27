<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\AddressForm\ToggleSelect;

use Yireo\LokiCheckout\Component\Checkout\AddressForm\AddressFormContext;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method AddressFormContext getContext()
 * @method ToggleSelectRepository getRepository()
 */
class ToggleSelectViewModel extends ComponentViewModel
{
    public function getJsData(): array
    {
        return [
            'value' => $this->getValue(),
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

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutAddress';
    }
}
