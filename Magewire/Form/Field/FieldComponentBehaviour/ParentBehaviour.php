<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour;

trait ParentBehaviour
{
    public function getNameInLayout(): string
    {
        return $this->getParent()->getNameInLayout();
    }

    public function getPlaceholder(): string
    {
        return (string)$this->getParent()->getPlaceholder();
    }

    public function hasMinLength(): bool
    {
        return is_numeric($this->getParent()->getMinLength());
    }

    public function getMinLength(): int
    {
        return (int)$this->getParent()->getMinLength();
    }

    public function hasMaxLength(): bool
    {
        return is_numeric($this->getParent()->getMaxLength());
    }

    public function getMaxLength(): int
    {
        return (int)$this->getParent()->getMaxLength();
    }
}
