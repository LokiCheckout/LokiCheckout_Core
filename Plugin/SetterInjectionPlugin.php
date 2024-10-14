<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Plugin;

use Magento\Framework\View\Element\AbstractBlock;
use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\ComponentResolver;
use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Util\FieldTemplateLocator;
use Yireo\FriggaCheckout\ViewModel\State\BillingAddressState;
use Yireo\FriggaCheckout\ViewModel\State\ShippingAddressState;

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

        if ($component instanceof BillingAddressFormFieldComponent) {
            $component->setBillingAddressState($this->billingAddressState);
        }

        if ($component instanceof ShippingAddressFormFieldComponent) {
            $component->setShippingAddressState($this->shippingAddressState);
        }

        return $component;
    }
}
