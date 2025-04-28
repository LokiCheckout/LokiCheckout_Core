<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\ViewModel\OptionModel\GenderOptionModel;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;

class GenderValidator implements ValidatorInterface
{
    public function __construct(
        private readonly GenderOptionModel $genderOptionModel,
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        $gender = trim((string)$value);

        if ($gender === '' || $gender === '0') {
            return true;
        }

        $genderOptions = $this->genderOptionModel->getOptions($component->getViewModel());
        if ($genderOptions === []) {
            return true;
        }

        if (false === array_key_exists($gender, $genderOptions)) {
            return['Invalid gender'];
        }

        return true;
    }
}
