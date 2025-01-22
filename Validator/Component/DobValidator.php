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

    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        if (false === is_string($value)) {
            return true;
        }

        $dob = strtotime($value);

        if ($dob > time()) {
            return [__('You can not be from the future', $this->minimumYears)];
        }

        $minimumAge = $this->minimumYears * 365 * 24 * 60 * 60;
        if (time() - $dob < $minimumAge) {
            return [__('You need to be at least %1 years old', $this->minimumYears)];
        }

        return true;
    }
}
