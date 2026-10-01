<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use LokiCheckout\Core\ViewModel\OptionModel\GenderOptionModel;
use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;

class GenderValidator implements ValidatorInterface
{
    public function __construct(
        private readonly GenderOptionModel $genderOptionModel,
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): bool|array
    {
        $gender = trim((string)$value, " \f\n\r\t\v\x00");

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
