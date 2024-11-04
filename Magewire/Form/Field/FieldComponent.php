<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\FieldTemplateLocatorBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\FilterBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\JsValidateBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ParentBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ValidateBehaviour;

abstract class FieldComponent extends Component implements FormFieldInterface
{
    use FilterBehaviour;
    use ValidateBehaviour;
    use JsValidateBehaviour;
    use FieldTemplateLocatorBehaviour;
    use ParentBehaviour;

    protected string $elementId = '';
    public string $value = '';
    public bool $locked = false;

    public function booted(): void
    {
        $this->listeners[static::class . '::afterSetValue'] = 'refresh';
    }

    protected function filter(string $value): string
    {
        return $value;
    }

    abstract public function getFieldName(): string;

    abstract public function getFieldLabel(): string;

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

    abstract public function save($value): void;

    public function isRequired(): bool
    {
        return true;
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
