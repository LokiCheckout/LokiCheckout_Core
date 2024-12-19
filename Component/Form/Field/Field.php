<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Yireo\LokiCheckout\Component\SetterInjection\InjectErrorContainerFactory;
use Yireo\LokiCheckout\Config\Config as LokiCheckoutConfig;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\FilterBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\ParentBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\ValidateBehaviour;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\SetterInjection\InjectFieldTemplateLocator;
use Yireo\LokiCheckout\Component\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;

abstract class Field extends Generic implements FieldInterface
{
    use InjectLokiCheckoutConfig;
    use InjectFieldTemplateLocator;
    use InjectErrorContainerFactory;

    use FilterBehaviour;
    use ValidateBehaviour;
    use ParentBehaviour;
    use StepBehaviour;

    protected string|bool|int $value = '';
    protected bool $valid = true;

    public function initialize(): void
    {
        $this->value = $this->getSavedValue();
    }

    public function getValue(): mixed
    {
        return $this->value;
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
            'css_classes' => $this->getBlock()->getCssClasses(),
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

    protected function getSavedValue(): string|bool|int
    {
        return '';
    }

    public function mutate(array $data = []): void
    {
        if (!isset($data['value'])) {
            return;
        }

        $value = $data['value'];
        $this->errorContainer->clear();
        $value = $this->filterValue($value);
        $this->value = $value;
        $this->valid = $this->validate();

        if (false === $this->errorContainer->hasErrors()) {
            $this->beforeSave($value);
            $this->save($value);
            $this->afterSave($value);
        }
    }

    protected function beforeSave($value): void
    {
    }

    abstract public function save($value): void;

    protected function afterSave($value): void
    {
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
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
            $this->errorContainer->addError( $error);
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
}
