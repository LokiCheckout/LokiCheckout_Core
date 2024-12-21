<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\GenericInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Mutator\MutatorInterface;

class SetterInjection
{
    public function __construct(
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
        private array $injectables = []
    ) {
    }

    /**
     * @param GenericInterface $component
     * @return GenericInterface
     */
    public function injectIntoComponent(Generic $component): Generic
    {
        foreach ($this->injectables as $injectableName => $injectable) {
            $method = 'set'.ucfirst($injectableName);
            if (method_exists($component, $method)) {
                call_user_func([$component, $method], $injectable);
            }
        }

        if ($component instanceof AddressField) {
            if ($this->isShippingComponent($component)) {
                $component->setStep('shipping'); // @todo: What about onestep?
                $component->setAddressState($this->shippingAddressState);
            }

            if ($this->isBillingComponent($component)) {
                $component->setStep('billing'); // @todo: What about onestep?
                $component->setAddressState($this->billingAddressState);
            }
        }

        return $component;
    }

    /**
     * @param MutatorInterface $component
     * @return MutatorInterface
     */
    public function injectIntoMutator(MutatorInterface $mutator): MutatorInterface
    {
        foreach ($this->injectables as $injectableName => $injectable) {
            $method = 'set'.ucfirst($injectableName);
            if (method_exists($mutator, $method)) {
                call_user_func([$mutator, $method], $injectable);
            }
        }

        $component = $mutator->getComponent();
        if ($component instanceof AddressField) {
            if ($this->isShippingComponent($component)) {
                $mutator->setStep('shipping'); // @todo: What about onestep?
                $mutator->setAddressState($this->shippingAddressState);
            }

            if ($this->isBillingComponent($component)) {
                $mutator->setStep('billing'); // @todo: What about onestep?
                $mutator->setAddressState($this->billingAddressState);
            }
        }

        return $mutator;
    }

    private function isShippingComponent(AddressField $component): bool
    {
        if (strstr($component->getBlock()->getNameInLayout(), 'shipping.address')) {
            return true;
        }

        return false;
    }

    private function isBillingComponent(AddressField $component): bool
    {
        if (strstr($component->getBlock()->getNameInLayout(), 'billing.address')) {
            return true;
        }

        return false;
    }
}
