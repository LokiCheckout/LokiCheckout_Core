<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\ToggleSelect;

use Loki\Components\Attribute\JsProperty;
use LokiCheckout\Core\Component\Checkout\Address\AddressFormContext;
use Loki\Components\Component\ComponentViewModel;

/**
 * @method AddressFormContext getContext()
 * @method ToggleSelectRepository getRepository()
 */
class ToggleSelectViewModel extends ComponentViewModel
{
    #[JsProperty(name: 'buttonLabelNewAddress')]
    public function getButtonLabelNewAddress(): string
    {
        if (str_contains($this->getComponentName(), 'billing')) {
            return (string)__('Add new billing address');
        }

        return (string)__('Add new shipping address');
    }

    #[JsProperty(name: 'buttonLabelSelectAddress')]
    public function getButtonLabelSelectAddress(): string
    {
        return (string)__('Select existing address from list');
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutAddress';
    }
}
