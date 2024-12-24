<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\ViewModel\OptionModel\GenderOptionModel;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;

class GenderValidator implements ValidatorInterface
{
    public function __construct(
        private GenderOptionModel $genderOptionModel,
    ) {
    }

    public function validate(ComponentInterface $component, mixed $value): bool
    {
        $gender = trim((string)$value);

        if (empty($gender)) {
            return true;
        }

        $genderOptions = $this->genderOptionModel->getOptions($component->getViewModel());
        if (empty($genderOptions)) {
            return true;
        }

        if (false === array_key_exists($gender, $genderOptions)) {
            $component->getMessages()->addError('Invalid gender');

            return false;
        }

        return true;
    }
}
