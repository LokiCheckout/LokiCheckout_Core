<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magewirephp\Magewire\Component;
use RuntimeException;
use Yireo\LokiCheckout\Util\Context;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

abstract class BillingAddressFormFieldComponent extends EavFormFieldComponent
{
    protected ?BillingAddressState $billingAddressState = null;

    public function boot(): void
    {
        if (false === $this->billingAddressState instanceof BillingAddressState) {
            throw new RuntimeException('BillingAddressState is not set for class "'.get_class($this).'"');
        }

        $this->value = (string)$this->billingAddressState->load($this->getAttributeCode());
    }

    public function updated($value, string $name)
    {
        $this->validate();

        $this->billingAddressState->save($this->getFieldName(), $value);
        $this->emit('billingStepSave');

        return $value;
    }

    public function getNameInLayout(): string
    {
        return 'loki.checkout.billing.'.$this->getFieldName();
    }

    /**
     * Added via \Yireo\LokiCheckout\Plugin\SetterInjectionPlugin
     *
     * @param BillingAddressState $billingAddressState
     * @return void
     */
    public function setBillingAddressState(BillingAddressState $billingAddressState)
    {
        $this->billingAddressState = $billingAddressState;
    }
}
