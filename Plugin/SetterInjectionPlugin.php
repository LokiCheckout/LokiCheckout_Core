<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Framework\View\Element\AbstractBlock;
use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\ComponentResolver;
use Yireo\LokiCheckout\Magewire\Generic\BillingAddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class SetterInjectionPlugin
{
    public function __construct(
        private FieldTemplateLocator $fieldTemplateLocator,
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
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
        if (method_exists($component, 'setFieldTemplateLocator')) {
            $component->setFieldTemplateLocator($this->fieldTemplateLocator);
        }

        if ($component instanceof BillingAddressFieldComponent) {
            $component->setAddressState($this->billingAddressState);
        }

        if ($component instanceof ShippingAddressFieldComponent) {
            $component->setAddressState($this->shippingAddressState);
        }

        return $component;
    }
}
