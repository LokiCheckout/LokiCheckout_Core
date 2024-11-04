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
    public bool $locked = false;

    public function boot(): void
    {
        $this->value = (string)$this->addressState->load($this->getFieldName());

        if ($this->request->isPreceding() && !empty($this->value)) {
            $this->validate();
        }
    }

    public function booted(): void
    {
        $this->listeners[static::class . '::afterSetValue'] = 'refresh';
    }


    public function getFieldName(): string
    {
        // @doc: You can override a field name in your own Component class
        // @doc: Field name is based upon non-namespaced classname
        $className = static::class;
        $className = str_replace('\Interceptor', '', $className);
        $baseClassname = basename(str_replace('\\', '/', $className));

        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $baseClassname), '_'));
    }

    public function getFieldLabel(): string
    {
        $parentBlock = $this->getParent();
        if ($parentBlock) {
            $fieldLabel = (string)$parentBlock->getFieldLabel();
            if (!empty($fieldLabel)) {
                return $fieldLabel;
            }
        }

        $attributeLabel = $this->getAttributeLabel();
        if (!empty($attributeLabel)) {
            return $attributeLabel;
        }

        return $this->getFieldName();
    }

    public function beforeSave($value): void
    {
    }

    public function save($value): void
    {
        $this->beforeSave($value);
        $this->addressState->save($this->getFieldName(), $value);

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

    public function afterSave($value): void
    {
    }

    public function isRequired(): bool
    {
        return (bool)$this->getAttribute()->getIsRequired();
    }
}
