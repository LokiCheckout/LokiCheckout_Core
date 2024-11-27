<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour;

use Magento\Framework\App\ObjectManager;
use RuntimeException;
use Yireo\LokiCheckout\Validator\Component\FieldValidatorInterface;
use Yireo\LokiCheckout\Validator\Component\FieldValidatorRegistry;

trait ValidateBehaviour
{
    protected array $validators = [];

    public function validate(): bool
    {
        $addressFieldValidatorRegistry = ObjectManager::getInstance()->get(FieldValidatorRegistry::class);
        $selectedValidators = $addressFieldValidatorRegistry->getSelectedValidators($this->getValidators());

        foreach ($selectedValidators as $validator) {
            if (false === $validator instanceof FieldValidatorInterface) {
                throw new RuntimeException('Wrong validator: '.var_export($validator, true));
            }

            $rt = $validator->validate($this);
            if ($rt === false) {
                return false;
            }
        }

        return true;
    }

    public function getValidators(): array
    {
        $validators = [];
        if ($this->isRequired()) {
            $validators[] = 'required';
        }

        if ($this->hasMinLength() || $this->hasMaxLength()) {
            $validators[] = 'length';
        }

        $validators = array_merge(
            $validators,
            $this->validators,
            array_values((array)$this->getParent()->getValidators())
        );

        $validators = array_unique($validators);
        return $validators;
    }
}
