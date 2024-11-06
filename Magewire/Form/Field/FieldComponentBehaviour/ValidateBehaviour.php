<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour;

use Magento\Framework\App\ObjectManager;
use Yireo\LokiCheckout\Validator\Component\FieldValidatorInterface;
use Yireo\LokiCheckout\Validator\Component\FieldValidatorRegistry;

trait ValidateBehaviour
{
    protected array $validators = [];

    public function validate(): bool
    {
        if ($this->isRequired() && empty($this->value)) {
            $this->error('value', (string)__('Value is required'));

            return false;
        }

        $addressFieldValidatorRegistry = ObjectManager::getInstance()->get(FieldValidatorRegistry::class);
        $selectedValidators = $addressFieldValidatorRegistry->getSelectedValidators($this->getValidators());

        foreach ($selectedValidators as $validator) {
            if (false === $validator instanceof FieldValidatorInterface) {
                throw new \Error('Wrong validator: '.var_export($validator, true));
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
        if ($this->hasMinLength() || $this->hasMaxLength()) {
            $this->validators[] = 'length';
        }

        return array_merge($this->validators, (array)$this->getParent()->getValidators());
    }
}
