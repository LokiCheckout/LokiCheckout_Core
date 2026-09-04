<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

class ValidationResult
{
    public function __construct(
        private readonly bool $valid,
        private readonly string $message = '',
    ) {
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
