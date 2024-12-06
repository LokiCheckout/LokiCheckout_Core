<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

class RequiredValidator implements FieldValidatorInterface
{
    public function validate(FieldComponent $component): bool
    {
        $value = $component->value;
        if (empty($value)) {
            $component->error('value', (string)__('Value is required'));

            return false;
        }

        return true;
    }
}
