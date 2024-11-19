<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\ComponentResolver;
use Yireo\LokiCheckout\Magewire\Form\Field\BillingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\ShippingAddressFieldComponentInterface;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;
use Yireo\LokiCheckout\ValueObject\StreetlinesFactory;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class SetterInjectionPlugin
{
    public function __construct(
        private FieldTemplateLocator $fieldTemplateLocator,
        private ShippingAddressState $shippingAddressState,
        private BillingAddressState $billingAddressState,
        private ScopeConfigInterface $scopeConfig,
        private CheckoutSession $checkoutSession,
        private CustomerSession $customerSession,
        private StreetlinesFactory $streetlinesFactory
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
        // @todo: Find a more generic way to do this (DI argument "injectables" and Reflection)
        if (method_exists($component, 'setScopeConfig')) {
            $component->setScopeConfig($this->scopeConfig);
        }

        if (method_exists($component, 'setCheckoutSession')) {
            $component->setCheckoutSession($this->checkoutSession);
        }

        if (method_exists($component, 'setCustomerSession')) {
            $component->setCustomerSession($this->customerSession);
        }

        if (method_exists($component, 'setFieldTemplateLocator')) {
            $component->setFieldTemplateLocator($this->fieldTemplateLocator);
        }

        if (method_exists($component, 'setStreetlinesFactory')) {
            $component->setStreetlinesFactory($this->streetlinesFactory);
        }

        if ($component instanceof BillingAddressFieldComponentInterface) {
            $component->setAddressState($this->billingAddressState);
        }

        if ($component instanceof ShippingAddressFieldComponentInterface) {
            $component->setAddressState($this->shippingAddressState);
        }

        return $component;
    }
}
