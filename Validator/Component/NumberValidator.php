<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Component\Form\Field\Field;

class NumberValidator implements FieldValidatorInterface
{
    public function validate(Field $component): bool
    {
        $value = $component->getValue();
        if (false === is_numeric($value)) {
            $component->getErrorContainer()->addError((string)__('Value must contain numbers only'));

            return false;
        }

        return true;
    }
}
