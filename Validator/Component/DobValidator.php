<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

class DobValidator implements FieldValidatorInterface
{
    public function __construct(
        private int $minimumYears = 10
    ) {
    }

    public function validate(FieldComponent $component): bool
    {
        $value = $component->getValue();
        if (false === is_string($value)) {
            return true;
        }

        $dob = strtotime($value);
        if ($dob > time()) {
            $msg = __('You can not be from the future', $this->minimumYears);
            $component->error('value', (string) $msg);

            return false;
        }

        $minimumAge = $this->minimumYears * 365 * 24 * 60 * 60;
        if (time() - $dob < $minimumAge) {
            $msg = __('You need to be at least %1 years old', $this->minimumYears);
            $component->error('value', (string) $msg);

            return false;
        }

        return true;
    }
}
