<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\ViewModel\PasswordComplexity;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;

class PasswordComplexityValidator implements ValidatorInterface
{
    public function __construct(
        private PasswordComplexity $passwordComplexity
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        if (empty($value)) {
            return true;
        }

        $minLength = $this->passwordComplexity->getMinimumLength();
        if (strlen($value) < $minLength) {
            return [(string)__('Your password needs to have a minimal length of %1', $minLength)];
        }

        return true;
    }
}
