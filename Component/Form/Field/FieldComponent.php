<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Yireo\LokiCheckout\Config\Config as LokiCheckoutConfig;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\FilterBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ParentBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\ValidateBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectFieldTemplateLocator;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;

abstract class FieldComponent extends GenericComponent implements FormFieldInterface
{
    use InjectLokiCheckoutConfig;
    use InjectFieldTemplateLocator;

    use FilterBehaviour;
    use ValidateBehaviour;
    use ParentBehaviour;
    use StepBehaviour;
    use AlpineDataBehaviour;

    public string|bool|int $value = '';
    public bool $valid = true;

    public function boot(): void
    {
        if (empty($this->value)) {
            $this->value = $this->getDefaultValue();
        }

        parent::boot();
    }

    public function booted(): void
    {
        parent::booted();

        if (false === $this->allowRendering) {
            $this->valid = true;
        }

        // @todo: This causes all fields to be red-bordered by default which is bad UI, but make it optional
        //if ($this->isRequired() && empty($this->value)) {
        //    $this->valid = false;
        //}
    }

    // @todo: Why does this not allow a listener to the trait method validate() directly?
    public function validateComponent(): bool
    {
        return $this->valid = $this->validate();
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
            'alias' => 'field',
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

    public function getInputType(): string
    {
        return 'text';
    }

    public function getDefaultValue(): string|bool|int
    {
        return '';
    }

    public function setValue(mixed $value): void
    {
        $this->clearErrors();
        $value = $this->filterValue($value);
        $this->value = $value;
        $this->valid = $this->validate();

        if (false === $this->hasErrors()) {
            $this->beforeSave($value);
            $this->save($value);
            $this->afterSave($value);
            //$this->emit($this->getAfterSaveEventName(), $this->value); // @todo: Is this needed?
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

    public function isDisabled(): bool
    {
        return false;
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

    public function getLokiCheckoutConfig(): LokiCheckoutConfig
    {
        return $this->lokiCheckoutConfig;
    }

    public function getHash(): string
    {
        $identifiers = [];
        $identifiers[] = $this->id;
        $identifiers[] = $this->isDisabled();
        $identifiers[] = $this->value;

        return md5(implode('-', $identifiers));
    }
}
