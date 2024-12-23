<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\Form\Field\AddressFieldViewModelViewModel;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\GenericInterface;
use Yireo\LokiCheckout\Component\GenericViewModelInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\Repository\MutatorInterface;

class SetterInjection
{
    public function __construct(
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
        private array $injectables = []
    ) {
    }

    /**
     * @param GenericViewModelInterface $component
     * @return GenericViewModelInterface
     */
    public function injectIntoViewModel(GenericViewModelInterface $viewModel): GenericViewModelInterface
    {
        foreach ($this->injectables as $injectableName => $injectable) {
            $method = 'set'.ucfirst($injectableName);
            if (method_exists($viewModel, $method)) {
                call_user_func([$viewModel, $method], $injectable);
            }
        }

        if ($viewModel instanceof AddressFieldViewModelViewModel) {
            if ($this->isShippingComponent($viewModel)) {
                $viewModel->setStep('shipping'); // @todo: What about onestep?
                $viewModel->setAddressState($this->shippingAddressState);
            }

            if ($this->isBillingComponent($viewModel)) {
                $viewModel->setStep('billing'); // @todo: What about onestep?
                $viewModel->setAddressState($this->billingAddressState);
            }
        }

        return $viewModel;
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
        if ($component instanceof AddressFieldViewModelViewModel) {
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

    private function isShippingComponent(AddressFieldViewModelViewModel $component): bool
    {
        if (strstr($component->getBlock()->getNameInLayout(), 'shipping.address')) {
            return true;
        }

        return false;
    }

    private function isBillingComponent(AddressFieldViewModelViewModel $component): bool
    {
        if (strstr($component->getBlock()->getNameInLayout(), 'billing.address')) {
            return true;
        }

        return false;
    }
}
