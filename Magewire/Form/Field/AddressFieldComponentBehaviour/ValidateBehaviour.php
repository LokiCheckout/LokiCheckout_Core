<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour;

use Magento\Framework\App\ObjectManager;
use Yireo\LokiCheckout\Validator\Component\AddressFieldValidatorInterface;
use Yireo\LokiCheckout\Validator\Component\AddressFieldValidatorRegistry;

trait ValidateBehaviour
{
    protected array $validators = [];

    public function validate(): bool
    {
        if ($this->isRequired() && empty($this->value)) {
            $this->error($this->getModelName(), (string)__('Value is required'));

            return false;
        }

        $addressFieldValidatorRegistry = ObjectManager::getInstance()->get(AddressFieldValidatorRegistry::class);
        $selectedValidators = $addressFieldValidatorRegistry->getSelectedValidators($this->getValidators());

        foreach ($selectedValidators as $validator) {
            if (false === $validator instanceof AddressFieldValidatorInterface) {
                throw new \Error('Wrong validator: '.var_export($validator, true));
            }

            $validator->validate($this);
        }

        return false === $this->hasErrors();
    }

    public function getValidators(): array
    {
        if ($this->hasMinLength() || $this->hasMaxLength()) {
            $this->validators[] = 'length';
        }

        return array_merge($this->validators, (array)$this->getParent()->getValidators());
    }
}
