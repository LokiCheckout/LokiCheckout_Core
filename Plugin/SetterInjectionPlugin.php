<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\View\Element\AbstractBlock;
use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\ComponentResolver;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class SetterInjectionPlugin
{
    public function __construct(
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
        private array $injectables = []
    ) {
    }

    /**
     * @param ComponentResolver $subject
     * @param Component $result
     * @param AbstractBlock $block
     * @return Component
     */
    public function afterResolve(ComponentResolver $subject, Component $component, AbstractBlock $block): Component
    {
        foreach ($this->injectables as $injectableName => $injectable) {
            $method = 'set' . ucfirst($injectableName);
            if (method_exists($component, $method)) {
                call_user_func([$component, $method], $injectable);
            }
        }

        if ($component instanceof ShippingAddressFieldComponentInterface) {
            $component->setStep('shipping'); // @todo: What about onestep?
            $component->setAddressState($this->shippingAddressState);
        }

        if ($component instanceof BillingAddressFieldComponentInterface) {
            $component->setStep('billing'); // @todo: What about onestep?
            $component->setAddressState($this->billingAddressState);
        }

        return $component;
    }
}
