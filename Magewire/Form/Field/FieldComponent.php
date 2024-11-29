<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Config\Config as LokiCheckoutConfig;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\FilterBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ParentBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ValidateBehaviour;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectFieldTemplateLocator;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;

abstract class FieldComponent extends Component implements FormFieldInterface
{
    use InjectLokiCheckoutConfig;
    use InjectFieldTemplateLocator;

    use FilterBehaviour;
    use ValidateBehaviour;
    use ParentBehaviour;
    use StepBehaviour;

    protected string $elementId = '';

    public string|bool|int $value = '';
    public bool $valid = true;
    public bool $visible = true;

    public function boot(): void
    {
        $this->value = $this->getDefaultValue();
        parent::boot();
    }

    public function booted(): void
    {
        parent::booted();
        if ($this->isRequired() && empty($this->value)) {
            $this->valid = false;
        }
    }

    public function addRefreshListener(string $listener)
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

    // @todo: Is this still needed?
    public function getFieldData(): array
    {
        return [
            'css_classes' => $this->getParent()->getCssClasses(),
        ];
    }

    public function getFieldTemplate(): string
    {
        if (false === $this->fieldTemplateLocator instanceof FieldTemplateLocator) {
            throw new \RuntimeException('FieldTemplateLocator not set for "'.get_class($this).'"');
        }

        return $this->fieldTemplateLocator?->getFieldTemplateFromComponent($this);
    }

    public function getListeners(): array
    {
        $parentBlock = $this->getParent();
        if ($parentBlock && $parentBlock->getListeners()) {
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

    public function getDefaultValue(): string
    {
        return '';
    }

    public function setValue(mixed $value): void
    {
        $value = $this->filterValue($value);
        $this->value = $value;
        $this->valid = $this->validate();

        if (false === $this->hasErrors()) {
            $this->beforeSave($value);
            $this->save($value);
            $this->afterSave($value);
            $this->emit('afterSave'.$this->snakeToCamel($this->getFieldName()));
        }
    }

    public function updatedValue(mixed $value): mixed
    {
        $this->setValue($value);

        return $value;
    }

    protected function beforeSave($value): void
    {
    }

    abstract public function save($value): void;

    protected function afterSave($value): void
    {
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function isVisible(): bool
    {
        return $this->visible;
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

    // @todo: Rename this to getDomElementId()?
    public function getElementId(): string
    {
        $elementId = !empty($this->elementId) ? $this->elementId : $this->getNameInLayout();

        return preg_replace('#([^a-zA-Z0-9]{1})#', '-', $elementId);
    }

    // @todo: Rename this to setDomElementId()?
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

    // @todo: Remove this?
    protected function snakeToCamel(string $text): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }

    // @todo: Rename to getAlpineData()
    public function getComponentData(): array
    {
        $rules = [];
        foreach ($this->getValidators() as $validator) {
            $rules[] = $validator;
        }

        return [
            'name' => $this->getJsName(),
            'wireId' => $this->id,
            'elementId' => $this->getElementId(),
            'step' => $this->getStep(),
            'value' => $this->getJsValue(),
            'valid' => $this->valid,
            'rules' => $rules,
        ];
    }

    public function getComponentDataAsJson(): string
    {
        return json_encode($this->getComponentData());
    }

    public function getLokiCheckoutConfig(): LokiCheckoutConfig
    {
        return $this->lokiCheckoutConfig;
    }
}
