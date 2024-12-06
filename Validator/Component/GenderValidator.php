<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;
use Yireo\LokiCheckout\ViewModel\OptionModel\GenderOptionModel;

class GenderValidator implements FieldValidatorInterface
{
    public function __construct(
        private GenderOptionModel $genderOptionModel,
    ) {
    }

    public function validate(FieldComponent $component): bool
    {
        $gender = trim($component->value);

        if (empty($gender)) {
            return true;
        }

        $genderOptions = $this->genderOptionModel->getOptions($component);
        if (empty($genderOptions)) {
            return true;
        }

        if (false === array_key_exists($gender, $genderOptions)) {
            $component->error('value', 'Invalid gender');

            return false;
        }

        return true;
    }
}
