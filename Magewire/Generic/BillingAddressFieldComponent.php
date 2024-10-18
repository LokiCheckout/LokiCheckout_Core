<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic;

use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

abstract class BillingAddressFieldComponent extends AddressFieldComponent
{

    public function boot(): void
    {
        $this->value = (string)$this->addressState->load($this->getAttributeCode());
    }

    public function save($value): void
    {
        $this->addressState->save($this->getFieldName(), $value);
        $this->emit('billingStepSave');
    }

    public function getNameInLayout(): string
    {
        return 'loki.checkout.billing.'.$this->getFieldName();
    }
}
