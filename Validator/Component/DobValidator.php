<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;

class DobValidator implements ValidatorInterface
{
    public function __construct(
        private int $minimumYears = 10
    ) {
    }

    public function validate(ComponentInterface $component, mixed $value): bool
    {
        if (false === is_string($value)) {
            return true;
        }

        $dob = strtotime($value);
        $messages = $component->getViewModel()->getMessageManager();

        if ($dob > time()) {
            $msg = __('You can not be from the future', $this->minimumYears);
            $messages->addLocalError((string)$msg);

            return false;
        }

        $minimumAge = $this->minimumYears * 365 * 24 * 60 * 60;
        if (time() - $dob < $minimumAge) {
            $msg = __('You need to be at least %1 years old', $this->minimumYears);
            $messages->addLocalError((string)$msg);

            return false;
        }

        return true;
    }
}
