<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AddressStateBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AttributeBehaviour;

abstract class AddressFieldComponent extends FieldComponent implements AddressFieldInterface
{
    use AttributeBehaviour;
    use AddressStateBehaviour;

    protected string $elementId = '';
    public bool $valid = true;

    public function boot(): void
    {
        $this->loadValue();

        // Validate the field when loading the HTML document (but not for AJAX calls)
        if ($this->request->isPreceding() && !empty($this->value)) {
            $this->valid = $this->validate();
        }

        parent::boot();
    }

    protected function loadValue(): void
    {
        $this->value = trim((string)$this->getAddressState()->load($this->getFieldName()));
    }

    // @todo: Move this into a trait or a child-class
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

    // @todo: Move this into a trait or a child-class
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

    public function getAddressType(): string
    {
        if ($this instanceof BillingAddressFieldComponentInterface) {
            return 'billing';
        }

        return 'shipping';
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

    public function save($value): void
    {
        $this->addressState->save($this->getFieldName(), $value);
    }

    public function isRequired(): bool
    {
        $required = $this->getParent()->getData('is_required');
        if (is_bool($required)) {
            return $required;
        }

        return (bool)$this->getAttribute()->getIsRequired();
    }

    protected function afterSave($value): void
    {
        $address = $this->snakeToCamel($this->getAddressType());
        $field = $this->snakeToCamel($this->getFieldName());
        $this->emit('afterSave'.$address.'Address'.$field);
        $this->emit('afterSave'.$address.'Address');

        parent::afterSave($value);
    }

    public function addFieldRefreshListener(string $fieldName): void
    {
        $address = $this->snakeToCamel($this->getAddressType());
        $field = $this->snakeToCamel($fieldName);
        $this->addRefreshListener('afterSave'.$address.'Address'.$field);
    }

    public function addAddressRefreshListener(string $address = ''): void
    {
        if (empty($address)) {
            $address = $this->getAddressType();
        }

        $address = $this->snakeToCamel($address);
        $this->addRefreshListener('afterSave'.$address.'Address');
    }

    public function addStepRefreshListener(string $step = ''): void
    {
        if (empty($step)) {
            $step = $this->getStep();
        }

        $step = $this->snakeToCamel($step);
        $this->addRefreshListener('afterSave'.$step.'Step');
    }
}
