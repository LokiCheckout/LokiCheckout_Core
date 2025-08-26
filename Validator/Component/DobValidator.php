<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;

class DobValidator implements ValidatorInterface
{
    public function __construct(
        private readonly int $minimumAge = 10
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): bool|array
    {
        if (false === is_string($value)) {
            return true;
        }

        $dob = strtotime($value);
        $minimumAge = $this->getMinimumAge($component);

        if ($dob > time()) {
            return [__('Please enter a valid date of birth', $minimumAge)];
        }

        $minimumAgeTimestamp = $minimumAge * 365 * 24 * 60 * 60;
        if (time() - $dob < $minimumAgeTimestamp) {
            return [__('You need to be at least %1 years old', $minimumAge)];
        }

        return true;
    }

    private function getMinimumAge(?ComponentInterface $component = null): int
    {
        if ($component instanceof ComponentInterface) {
            $minimumAge = (int)$component->getBlock()->getMinimumAge();
            if ($minimumAge > 0) {
                return $minimumAge;
            }
        }

        return $this->minimumAge;
    }
}
