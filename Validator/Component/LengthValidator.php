<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

class LengthValidator implements FieldValidatorInterface
{
    public function validate(FieldComponent $component): bool
    {
        $value = $component->getValue();
        if (false === is_string($value)) {
            return true;
        }

        if ($component->hasMinLength() && strlen($value) < $component->getMinLength()) {
            $msg = __('This value should be %1 characters or more in length', $component->getMinLength());
            $component->error('value', (string) $msg);

            return false;
        }

        if ($component->hasMaxLength() && strlen($value) > $component->getMaxLength()) {
            $msg = __('This value should be %1 characters or less in length: '.$value, $component->getMaxLength());
            $component->error('value', (string) $msg);

            return false;
        }

        return true;
    }
}
