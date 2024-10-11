<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Generic;

use Magewirephp\Magewire\Component\Form;
use Magewirephp\Magewire\Exception\AcceptableException;
use Rakit\Validation\Validator;
use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\AttributeBehaviour;
use Yireo\FriggaCheckout\Util\Context;

abstract class EavFormFieldComponent extends Form implements FormFieldInterface, EavFormFieldInterface
{
    use AttributeBehaviour;

    const FIELD_NAME = 'value';
    const FIELD_TYPE = 'text';
    const TEXT_TYPE = 'text';

    public string $value = '';

    public function __construct(
        Validator $validator,
        protected Context $context,
    ) {
        parent::__construct($validator);
    }

    public function boot(): void
    {
        $this->value = (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getData($this->getAttributeCode());
    }

    public function getFieldType(): string
    {
        return $this->context->getFieldTypeLocator()->getFieldTypeFromComponent($this);
    }

    public function getFieldName(): string
    {
        return static::FIELD_NAME;
    }

    public function getTextType(): string
    {
        return static::TEXT_TYPE;
    }

    public function getValue(): string
    {
        return (string)$this->value;
    }

    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

    public function isRequired(): bool
    {
        return (bool)$this->getAttribute()->getIsRequired();
    }

    public function isValid(): bool
    {
        if ($this->isRequired() && empty($this->value)) {
            $this->error('value', (string)__('Value is required'));
            return false;
        }

        try {
            $this->validate();
        } catch (AcceptableException $e) {
        }

        return false === $this->hasErrors();
    }

    protected function setErrorsFromValidation(true|array $errors): bool
    {
        if (true === $errors) {
            return true;
        }

        foreach ($errors as $error) {
            $this->error('value', $error);
        }

        return false;
    }

    protected function beforeValidate()
    {
    }
}
