<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Yireo\LokiCheckout\Component\Form\Field\Field;
use Yireo\LokiCheckout\ViewModel\OptionModel\GenderOptionModel;

class GenderValidator implements FieldValidatorInterface
{
    public function __construct(
        private GenderOptionModel $genderOptionModel,
    ) {
    }

    public function validate(Field $component): bool
    {
        $gender = trim($component->getValue());

        if (empty($gender)) {
            return true;
        }

        $genderOptions = $this->genderOptionModel->getOptions($component);
        if (empty($genderOptions)) {
            return true;
        }

        if (false === array_key_exists($gender, $genderOptions)) {
            $component->getErrorContainer()->addError('Invalid gender');

            return false;
        }

        return true;
    }
}
