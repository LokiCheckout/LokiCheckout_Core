<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AddressStateBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AttributeBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\FieldTemplateLocatorBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\FilterBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\JsValidateBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\ParentBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\ValidateBehaviour;

abstract class AddressFieldComponent extends Component implements FormFieldInterface, AddressFieldInterface
{
    use AttributeBehaviour;
    use FilterBehaviour;
    use ValidateBehaviour;
    use JsValidateBehaviour;
    use FieldTemplateLocatorBehaviour;
    use AddressStateBehaviour;
    use ParentBehaviour;

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

        $baseClassname = basename(str_replace('\\', '/', static::class));

        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $baseClassname), '_'));
    }

    public function getFieldLabel(): string
    {
        $parentBlock = $this->getParent();
        if ($parentBlock && $parentBlock->getFieldLabel()) {
            return (string)$parentBlock->getFieldLabel();
        }

        return $this->getAttributeLabel();
    }

    public function getListeners(): array
    {
        $parentBlock = $this->getParent();
        if ($parentBlock && $parentBlock->getListeners()) {
            // @todo: Test whether this is properly working or not
            $this->listeners = array_merge($this->listeners, (array)$parentBlock->getListeners());
        }

        return $this->listeners;
    }

    public function getInputType(): string
    {
        return 'text'; // @doc: Example for overriding this to "email"
    }

    public function getModelName(): string
    {
        return 'value';
    }

    public function getValue(): string
    {
        return (string)$this->value;
    }

    public function setValue(mixed $value)
    {
        $value = $this->filterValue($value);
        $this->value = $value;
        $this->validate();

        if (false === $this->hasErrors()) {
            $this->save($value);
            $this->emit(static::class . '::afterSetValue');
        }
    }

    public function updatedValue($value)
    {
        $this->setValue($value);
        return $value;
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

    public function isVisible(): bool
    {
        return true; // @doc: Example for overriding this
    }

    public function getJsValue(): string
    {
        return str_replace("\n", '\n', $this->value); // @todo: Is this good enough?
    }

    public function getJsName(): string
    {
        $name = ucwords($this->getNameInLayout(), '_.-');

        return preg_replace('#[_.-]+#', '', $name);
    }

    public function getElementId(): string
    {
        $elementId = !empty($this->elementId) ? $this->elementId : $this->getNameInLayout();
        return preg_replace('#([^a-zA-Z0-9]{1})#', '-', $elementId);
    }

    public function setElementId(string $elementId)
    {
        $this->elementId = $elementId;
    }

    protected function setErrorsFromValidation(true|array $errors): bool
    {
        if (true === $errors) {
            return true;
        }

        foreach ($errors as $error) {
            $this->error('value', $error);
        }

        return false;
    }

    protected function snakeToCamel(string $text): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }
}
