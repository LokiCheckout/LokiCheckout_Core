<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\FieldTemplateLocatorBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\FilterBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\JsValidateBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ParentBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ValidateBehaviour;

abstract class FieldComponent extends Component implements FormFieldInterface
{
    use FilterBehaviour;
    use ValidateBehaviour;
    use JsValidateBehaviour;
    use FieldTemplateLocatorBehaviour;
    use ParentBehaviour;
    use StepBehaviour;

    protected string $elementId = '';

    public string $value = '';
    public bool $valid = true;

    public function booted(): void
    {
        //$this->addRefreshListener(static::class.'::afterSetValue'); // @todo: Is this still needed in the first place?
    }

    protected function addRefreshListener(string $listener)
    {
        $this->listeners[$listener] = 'refresh';
    }

    // @todo: Why does this not allow a listener to the trait method validate() directly?
    public function validateComponent(): bool
    {
        return $this->valid = $this->validate();
    }

    protected function filter(string $value): string
    {
        return $value;
    }

    public function getFieldName(): string
    {
        $className = static::class;
        $className = str_replace('\Interceptor', '', $className);
        $className = str_replace('\Proxy', '', $className);
        $baseClassname = basename(str_replace('\\', '/', $className));

        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $baseClassname), '_'));
    }

    abstract public function getFieldLabel(): string;

    public function getFieldData(): array
    {
        return [
            'css_classes' => $this->getParent()->getCssClasses(),
        ];
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
        return 'text';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(mixed $value)
    {
        $value = $this->filterValue($value);
        $this->value = $value;
        $this->valid = $this->validate();

        if (false === $this->hasErrors()) {
            $this->save($value);
            $this->emit(static::class.'::afterSetValue');
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
        return false;
    }

    public function isVisible(): bool
    {
        return true; // @todo: Is this still a handy thing?
    }

    public function isDisabled(): bool
    {
        return false;
    }

    public function getJsValue(): string
    {
        return str_replace("\n", '\n', $this->value); // @todo: Is this good enough?
    }

    public function getJsName(): string
    {
        // @todo: Use CamelCaseConvertor instead or even getParentBlock()->getJsId()
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

        $this->valid = false;

        return false;
    }

    protected function snakeToCamel(string $text): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }
}
