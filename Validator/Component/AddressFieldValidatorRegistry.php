<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use InvalidArgumentException;

class AddressFieldValidatorRegistry
{
    public function __construct(
        private array $validators = []
    ) {
        foreach ($this->validators as $validator) {
            if (false === $validator instanceof AddressFieldValidatorInterface) {
                throw new InvalidArgumentException('Validator instance is not supported');
            }
        }
    }

    public function getSelectedValidators(array $selectedValidators = []): array
    {
        $validators = [];
        foreach ($selectedValidators as $validator) {
            if (array_key_exists($validator, $this->validators)) {
                $validators[$validator] = $validator;
            }
        }

        return $validators;
    }
}
