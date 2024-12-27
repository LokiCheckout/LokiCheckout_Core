<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field;

use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;

/**
 * @method FieldContext getContext()
 */
abstract class FieldViewModel extends GenericViewModel implements FieldViewModelInterface
{
    protected string $step = '';

    public function getFieldName(): string
    {
        $parts = explode('.', $this->getNameInLayout());
        return array_pop($parts);
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutFieldComponent';
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
        return $this->getContext()->fieldTemplateLocator->getFieldTemplateFromViewModel($this);
    }

    public function getInputType(): string
    {
        return 'text';
    }

    public function isValid(): bool
    {
        return true;
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function isDisabled(): bool
    {
        return (bool)$this->getBlock()->getDisabled();
    }

    public function getNameInLayout(): string
    {
        return $this->getBlock()->getNameInLayout();
    }

    public function getPlaceholder(): string
    {
        return (string)$this->getBlock()->getPlaceholder();
    }

    public function hasMinLength(): bool
    {
        return is_numeric($this->getBlock()->getMinLength());
    }

    public function getMinLength(): int
    {
        return (int)$this->getBlock()->getMinLength();
    }

    public function hasMaxLength(): bool
    {
        return is_numeric($this->getBlock()->getMaxLength());
    }

    public function getMaxLength(): int
    {
        return (int)$this->getBlock()->getMaxLength();
    }

    public function hasMin(): bool
    {
        return !empty($this->getBlock()->getMin());
    }

    public function getMin(): string
    {
        return (string)$this->getBlock()->getMin();
    }

    public function hasMax(): bool
    {
        return !empty($this->getBlock()->getMax());
    }

    public function getMax(): string
    {
        return (string)$this->getBlock()->getMax();
    }

    public function setStep(string $step): void
    {
        $this->step = $step;
    }

    public function getStep(): string
    {
        if (!empty($this->step)) {
            return $this->step;
        }

        $this->step = $this->getContext()->stepProvider->getStep($this->getBlock());
        return $this->step;
    }
}
