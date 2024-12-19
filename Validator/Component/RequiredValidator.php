<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Component\Form\Field\Field;

class RequiredValidator implements FieldValidatorInterface
{
    public function validate(Field $component): bool
    {
        $value = $component->getValue();
        if (empty($value)) {
            $component->getErrorContainer()->addError((string)__('Value is required'));

            return false;
        }

        return true;
    }
}
