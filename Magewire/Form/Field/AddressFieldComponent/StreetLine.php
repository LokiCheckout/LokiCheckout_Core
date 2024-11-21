<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\SetterInjection\InjectScopeConfig;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectStreetlinesFactory;

class StreetLine extends AddressFieldComponent
{
    use InjectScopeConfig;
    use InjectStreetlinesFactory;

    protected int $index = 0;

    public function booted(): void
    {
        parent::booted();
        $this->addRefreshListener('StreetUpdated');
    }

    protected function loadValue(): void
    {
        $street = (string)$this->addressState->load('street');
        $streetlines = $this->streetlinesFactory->createFromStreet($street);
        $this->value = $streetlines->getLineByIndex($this->index);
    }

    protected function saveValue($value)
    {
        $street = (string)$this->addressState->load('street');
        $streetlines = $this->streetlinesFactory->createFromStreet($street);
        $streetlines = $streetlines->setLineByIndex($this->index, $this->value);
        $this->addressState->save('street', $streetlines->toString());
        $this->emit('StreetUpdated'); // @todo: Standardize event names
    }

    public function isVisible(): bool
    {
        $streetLines = (int)$this->scopeConfig->getValue('customer/address/street_lines');
        return $streetLines > $this->index;
    }

    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if (!empty($fieldLabel)) {
            return $fieldLabel;
        }

        if ($this->index > 0) {
            return '';
        }

        return $this->getFieldName();
    }
}
