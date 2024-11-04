<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use InvalidArgumentException;

class FieldValidatorRegistry
{
    public function __construct(
        private array $validators = []
    ) {
        foreach ($this->validators as $validator) {
            if (false === $validator instanceof FieldValidatorInterface) {
                throw new InvalidArgumentException('Validator instance is not supported');
            }
        }
    }

    public function getSelectedValidators(array $selectedValidators = []): array
    {
        $validators = [];
        foreach ($selectedValidators as $validatorName) {
            if (array_key_exists($validatorName, $this->validators)) {
                $validators[$validatorName] = $this->validators[$validatorName];
            }
        }

        return $validators;
    }
}
