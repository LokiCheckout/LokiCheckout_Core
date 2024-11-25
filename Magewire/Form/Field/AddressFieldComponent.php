<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AddressStateBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AttributeBehaviour;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

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
        $this->value = trim((string)$this->getAddressState()->load($this->getFieldName()));
    }

    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if (!empty($fieldLabel)) {
            return $fieldLabel;
        }

        $attributeLabel = $this->getAttributeLabel();
        if (!empty($attributeLabel)) {
            return $attributeLabel;
        }

        return $this->getFieldName();
    }

    protected function getFieldLabelFromLayout(): string
    {
        $parentBlock = $this->getParent();
        if (false === $parentBlock instanceof Template) {
            return '';
        }

        $fieldLabel = (string)$parentBlock->getFieldLabel();
        if (empty($fieldLabel)) {
            return '';
        }

        if ($fieldLabel === '-') {
            return '';
        }

        return $fieldLabel;
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
        $required = $this->getParent()->getData('is_required');
        if (is_bool($required)) {
            return $required;
        }

        return (bool)$this->getAttribute()->getIsRequired();
    }
}
