<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Yireo\LokiCheckout\Component\GenericViewModel;
use Yireo\LokiCheckout\Config\Config as LokiCheckoutConfig;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\ParentBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\StepBehaviour;
use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Component\SetterInjection\InjectFieldTemplateLocator;
use Yireo\LokiCheckout\Component\SetterInjection\InjectLokiCheckoutConfig;
use Yireo\LokiCheckout\Util\FieldTemplateLocator;

abstract class FieldViewModel extends GenericViewModel implements FieldViewModelInterface
{
    use InjectLokiCheckoutConfig;
    use InjectFieldTemplateLocator;

    use ParentBehaviour;
    use StepBehaviour;


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
        $parts = explode('.', $this->getNameInLayout());
        return array_pop($parts);
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

        return $this->fieldTemplateLocator?->getFieldTemplateFromViewModel($this);
    }

    public function getInputType(): string
    {
        return 'text';
    }

    protected function getSavedValue(): string|bool|int
    {
        return '';
    }

    public function isDisabled(): bool
    {
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
