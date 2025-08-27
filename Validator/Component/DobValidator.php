<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;

class DobValidator implements ValidatorInterface
{
    public function __construct(
        private readonly int $minimumAge = 10,
        private readonly int $maximumAge = 125
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): bool|array
    {
        if (false === is_string($value)) {
            return true;
        }

        $dob = strtotime($value);
        if (false === $dob) {
            return [__('Please enter a valid date')];
        }

        if ($dob > time()) {
            return [__('Please enter a valid date of birth')];
        }

        $minimumAge = $this->getMinimumAge($component);
        $minimumAgeTimestamp = $minimumAge * 365 * 24 * 60 * 60;
        if (time() - $dob < $minimumAgeTimestamp) {
            return [__('You need to be at least %1 years old', $minimumAge)];
        }

        $maximumAge = $this->getMaximumAge($component);
        $maximumAgeTimestamp = $maximumAge * 365 * 24 * 60 * 60;
        if (time() - $dob > $maximumAgeTimestamp) {
            return [__('You need to be younger than %1 years old', $maximumAge)];
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

    private function getMaximumAge(?ComponentInterface $component = null): int
    {
        if ($component instanceof ComponentInterface) {
            $maximumAge = (int)$component->getBlock()->getMaximumAge();
            if ($maximumAge > 0) {
                return $maximumAge;
            }
        }

        return $this->maximumAge;
    }
}
