<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\QuoteField;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour\AttributeBehaviour;

abstract class QuoteFieldViewModel extends FieldViewModel implements QuoteFieldViewModelInterface
{
    use AttributeBehaviour;

    protected function getSavedValue(): string|bool|int
    {
        return trim((string) $this->checkoutSession->getQuote()->getData($this->getFieldName()));
    }

    public function getFieldLabel(): string
    {
        $parentBlock = $this->getBlock();
        if ($parentBlock) {
            $fieldLabel = (string)$parentBlock->getFieldLabel();
            if (!empty($fieldLabel)) {
                return $fieldLabel;
            }
        }

        $attributeLabel = $this->getAttributeLabel();
        if (!empty($attributeLabel)) {
            return $attributeLabel;
        }

        return $this->getFieldName();
    }

    public function save($value): void
    {
        $this->beforeSave($value);

        $this->checkoutSession->getQuote()->setData($this->getFieldName(), $value);
        $this->checkoutSession->getQuote()->save(); // @todo
    }

    public function isRequired(): bool
    {
        return (bool)$this->getAttribute()->getIsRequired();
    }
}
