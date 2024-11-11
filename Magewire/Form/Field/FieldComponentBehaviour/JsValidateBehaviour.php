<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour;

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
