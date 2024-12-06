<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

class NumberValidator implements FieldValidatorInterface
{
    public function validate(FieldComponent $component): bool
    {
        $value = $component->value;
        if (false === is_numeric($value)) {
            $component->error('value', (string)__('Value must contain numbers only'));

            return false;
        }

        return true;
    }
}
