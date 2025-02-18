<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ValueObject\Adminhtml;

class CheckResult
{
    public function __construct(
        private string $label,
        private mixed $value,
        private bool $valid,
        private string $recommendation
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getRecommendation(): string
    {
        return $this->recommendation;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}
