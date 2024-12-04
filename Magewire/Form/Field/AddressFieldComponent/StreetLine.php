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

    public function boot(): void
    {
        parent::boot();
        $this->addFieldRefreshListener('street');

        $streetLines = (int)$this->scopeConfig->getValue('customer/address/street_lines');
        if ($streetLines <= $this->index) {
            $this->visible = false;
        }
    }

    protected function loadValue(): void
    {
        $street = (string)$this->addressState->load('street');
        $streetlines = $this->streetlinesFactory->createFromStreet($street);
        $this->value = $streetlines->getLineByIndex($this->index);
    }

    public function save($value): void
    {
        $street = (string)$this->addressState->load('street');
        $streetlines = $this->streetlinesFactory->createFromStreet($street);
        $streetlines = $streetlines->setLineByIndex($this->index, $this->value);
        $this->addressState->save('street', $streetlines->toString());
    }

    protected function afterSave($value): void
    {
        parent::afterSave($value);
        $addressPrefix = $this->snakeToCamel($this->getAddressType());
        $this->emit('afterSave'.$addressPrefix.'AddressStreet');
        $this->emit('afterSaveStreet');
    }

    public function isRequired(): bool
    {
        if ($this->index === 0) {
            return true;
        }

        return parent::isRequired();
    }

    public function getFieldName(): string
    {
        return 'street'.$this->index;
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

    public function getIndex(): int
    {
        return $this->index;
    }
}
