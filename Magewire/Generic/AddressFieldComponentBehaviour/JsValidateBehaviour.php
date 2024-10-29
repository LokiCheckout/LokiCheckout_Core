<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponentBehaviour;

use Magento\Framework\App\ObjectManager;
use Yireo\LokiCheckout\Validator\Component\AddressFieldValidatorInterface;
use Yireo\LokiCheckout\Validator\Component\AddressFieldValidatorRegistry;

trait JsValidateBehaviour
{
    protected array $jsValidators = [];

    public function getJsValidators(): array
    {
        $validators = $this->jsValidators;

        if ($this->isRequired()) {
            $validators = array_merge(['required'], $validators);
        }

        $layoutValidators = (array)$this->getParent()->getJsValidators(); // @todo: Test that this is working
        if (!empty($layoutValidators)) {
            $validators = array_merge($validators, (array)$layoutValidators);
        }

        return $validators;
    }
}
