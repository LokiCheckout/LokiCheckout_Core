<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

interface TokenValidatorInterface
{
    /**
     * Verify a reCAPTCHA response token for the given form id.
     */
    public function validate(string $token, string $formId): ValidationResult;
}
