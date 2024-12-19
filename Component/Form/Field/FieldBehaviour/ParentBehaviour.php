<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour;

trait ParentBehaviour
{
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
}
