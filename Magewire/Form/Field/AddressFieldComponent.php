<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AddressStateBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AttributeBehaviour;

abstract class AddressFieldComponent extends FieldComponent implements AddressFieldInterface
{
    use AttributeBehaviour;
    use AddressStateBehaviour;

    protected string $elementId = '';
    public string $value = '';
    public bool $valid = true;

    public function boot(): void
    {
        $this->loadValue();
        if ($this->request->isPreceding() && !empty($this->value)) {
            $this->valid = $this->validate();
        }
    }

    protected function loadValue(): void
    {
        $this->value = trim((string)$this->addressState->load($this->getFieldName()));
    }

    public function getFieldLabel(): string
    {
        $parentBlock = $this->getParent();
        if ($parentBlock) {
            $fieldLabel = (string)$parentBlock->getFieldLabel();
            if (!empty($fieldLabel)) {
                if ($fieldLabel === '-') {
                    return '';
                }

                return $fieldLabel;
            }
        }

        $attributeLabel = $this->getAttributeLabel();
        if (!empty($attributeLabel)) {
            return $attributeLabel;
        }

        return $this->getFieldName();
    }


    public function getStep(): string
    {
        if ($this instanceof ShippingAddressFieldComponentInterface) {
            return 'shipping';
        }

        if ($this instanceof BillingAddressFieldComponentInterface) {
            return 'billing';
        }

        return 'unknown';
    }

    protected function beforeSave($value): void
    {
    }

    protected function saveValue($value)
    {
        $this->addressState->save($this->getFieldName(), $value);
    }

    public function save($value): void
    {
        $this->beforeSave($value);
        $this->saveValue($value);

        if ($this instanceof ShippingAddressFieldComponentInterface) {
            $this->emit('shippingAddress'.$this->snakeToCamel($this->getFieldName()).'Save');
            $this->emit('shippingStepSave');
        }

        if ($this instanceof BillingAddressFieldComponentInterface) {
            $this->emit('billingAddress'.$this->snakeToCamel($this->getFieldName()).'Save');
            $this->emit('billingStepSave');
        }

        $this->afterSave($value);
    }

    protected function afterSave($value): void
    {
    }

    public function isRequired(): bool
    {
        return (bool)$this->getAttribute()->getIsRequired();
    }
}
