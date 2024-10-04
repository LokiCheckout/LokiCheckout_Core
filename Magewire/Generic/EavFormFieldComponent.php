<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Generic;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\Util\Context;

abstract class EavFormFieldComponent extends Component implements FormFieldInterface, EavFormFieldInterface
{
    const ATTRIBUTE_CODE = '';
    const FIELD_NAME = 'value';
    const FIELD_TYPE = 'text';
    const TEXT_TYPE = 'text';

    public string $value = '';

    public function __construct(
        protected Context $context,
    ) {
    }

    public function boot(): void
    {
        $this->value = (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getData($this->getAttributeCode());

        $this->error('value', 'fsfs');
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

    public function getAttributeCode(): string
    {
        return static::ATTRIBUTE_CODE;
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
        if (empty($this->value) && $this->isRequired()) {
            $this->error('value', 'This value is required.');
        }

        return false === $this->hasErrors();
    }

    public function getAttributeLabel(): string
    {
        return (string)$this->getAttribute()->getFrontendLabel();
    }

    protected function getAttribute(): AbstractAttribute
    {
        return $this->context->getAttribute($this->getAttributeCode());
    }
}
