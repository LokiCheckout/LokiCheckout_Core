<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Magento\Framework\App\ObjectManager;
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

        $validators = array_merge($this->validators, (array)$this->getParent()->getValidators());
        $validatorRegistry = ObjectManager::getInstance()->get(AddressFieldValidatorRegistry::class);
        $selectedValidators = $validatorRegistry->getSelectedValidators($validators);

        foreach ($selectedValidators as $validator) {
            $validator->validate($this);
        }

        return false === $this->hasErrors();
    }
}
