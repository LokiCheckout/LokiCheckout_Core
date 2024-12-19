<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Yireo\LokiCheckout\Component\Form\Field\BillingAddressFieldInterface;
use Yireo\LokiCheckout\Component\Form\Field\ShippingAddressFieldInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentInterface;

class SetterInjection
{
    public function __construct(
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
        private array $injectables = []
    ) {
    }

    /**
     * @param ComponentInterface $component
     * @return ComponentInterface
     */
    public function injectDependencies(ComponentInterface $component): ComponentInterface
    {
        foreach ($this->injectables as $injectableName => $injectable) {
            $method = 'set' . ucfirst($injectableName);
            if (method_exists($component, $method)) {
                call_user_func([$component, $method], $injectable);
            }
        }

        if ($component instanceof ShippingAddressFieldInterface) {
            $component->setStep('shipping'); // @todo: What about onestep?
            $component->setAddressState($this->shippingAddressState);
        }

        if ($component instanceof BillingAddressFieldInterface) {
            $component->setStep('billing'); // @todo: What about onestep?
            $component->setAddressState($this->billingAddressState);
        }

        return $component;
    }
}
