<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;
use Yireo\LokiComponents\Messages\LocalMessage;

/**
 * @method FieldContext getContext()
 */
abstract class FieldViewModel extends CheckoutViewModel implements FieldViewModelInterface
{
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
        return $this->getContext()->getFieldTemplateLocator()->getFieldTemplateFromViewModel($this);
    }

    public function getInputType(): string
    {
        return 'text';
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

    public function hasAutocomplete(): bool
    {
        return !empty($this->getAutocomplete());
    }

    public function getAutocomplete(): string
    {
        return (string)$this->getBlock()->getAutocomplete();
    }

    public function hasMinLength(): bool
    {
        return $this->getMinLength() > 0;
    }

    public function getMinLength(): int
    {
        return (int)$this->getBlock()->getMinLength();
    }

    public function hasMaxLength(): bool
    {
        return $this->getMaxLength() > 0;
    }

    public function getMaxLength(): int
    {
        return (int)$this->getBlock()->getMaxLength();
    }

    public function hasMin(): bool
    {
        return $this->getMin() > 0;
    }

    public function getMin(): string
    {
        return (string)$this->getBlock()->getMin();
    }

    public function hasMax(): bool
    {
        return $this->getMax() > 0;
    }

    public function getMax(): string
    {
        return (string)$this->getBlock()->getMax();
    }
}
