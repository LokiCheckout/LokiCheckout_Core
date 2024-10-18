<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

abstract class ShippingAddressFieldComponent extends AddressFieldComponent
{
    public function boot(): void
    {
        $this->value = (string)$this->addressState->load($this->getFieldName());

        if (!empty($this->value)) {
            $this->validate();
        }
    }

    public function save($value): void
    {
        $this->addressState->save($this->getFieldName(), $value);
        $this->emit('shippingStepSave');
    }

    public function getNameInLayout(): string
    {
        return 'loki.checkout.shipping.'.$this->getFieldName();
        return $this->getParent()->getNameInLayout(); // @todo: Why does this not work?
    }
}
