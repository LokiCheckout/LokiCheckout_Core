<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

// @todo: Persist these errors
class ErrorContainer
{
    private array $errors = [];

    public function __construct() {
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function clear(): void
    {
        $this->errors = [];
    }
}
