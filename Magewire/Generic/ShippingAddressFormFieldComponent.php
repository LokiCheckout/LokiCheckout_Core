<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magewirephp\Magewire\Component;
use RuntimeException;
use Yireo\LokiCheckout\Util\Context;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

abstract class ShippingAddressFormFieldComponent extends EavFormFieldComponent
{
    protected ?ShippingAddressState $shippingAddressState = null;

    public function boot(): void
    {
        if (false === $this->shippingAddressState instanceof ShippingAddressState) {
            throw new RuntimeException('ShippingAddressState is not set for class "'.get_class($this).'"');
        }

        $this->value = (string)$this->shippingAddressState->load($this->getFieldName());
    }

    public function updatedValue($value)
    {
        $this->validate();

        $this->shippingAddressState->save($this->getFieldName(), $value);
        $this->emit('shippingStepSave');

        return $value;
    }

    public function getNameInLayout(): string
    {
        return 'loki.checkout.shipping.'.$this->getFieldName();
    }

    /**
     * Added via \Yireo\LokiCheckout\Plugin\SetterInjectionPlugin
     *
     * @param ShippingAddressState $shippingAddressState
     * @return void
     */
    public function setShippingAddressState(ShippingAddressState $shippingAddressState)
    {
        $this->shippingAddressState = $shippingAddressState;
    }
}
