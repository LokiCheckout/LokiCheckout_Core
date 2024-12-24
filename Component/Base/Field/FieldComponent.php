<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Field;

use Yireo\LokiComponents\Component\MutableComponent;

die('Do not use FieldComponent');; // @todo Remove
class FieldComponent extends MutableComponent
{
    protected mixed $value = '';
    protected bool $valid = true;

    public function getValidators(): array
    {
        $validators = [];
        if ($this->getRepository()->getFieldName()) {
            $validators[] = $this->getRepository()->getFieldName();
        }

        if ($this->isRequired()) {
            $validators[] = 'required';
        }

        if ($this->hasMinLength() || $this->hasMaxLength()) {
            $validators[] = 'length';
        }

        $layoutValidators = (array)$this->getViewModel()->getBlock()->getValidators();
        if (!empty($layoutValidators)) {
            $validators = array_merge($validators, array_values((array)$layoutValidators));
        }

        return array_unique($validators);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isRequired(): bool
    {
        return false;
    }
}
