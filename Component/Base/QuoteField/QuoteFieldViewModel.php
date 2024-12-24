<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\QuoteField;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

/**
 * @method QuoteFieldContext getContext()
 */
abstract class QuoteFieldViewModel extends FieldViewModel implements QuoteFieldViewModelInterface
{
    public function getFieldLabel(): string
    {
        $parentBlock = $this->getBlock();
        if ($parentBlock) {
            $fieldLabel = (string)$parentBlock->getFieldLabel();
            if (!empty($fieldLabel)) {
                return $fieldLabel;
            }
        }

        $attributeLabel = $this->getContext()->attributeProvider->getAttributeLabel($this->getFieldName());
        if (!empty($attributeLabel)) {
            return $attributeLabel;
        }

        return $this->getFieldName();
    }

    public function isRequired(): bool
    {
        return (bool)$this->getContext()->attributeProvider->getAttribute($this->getFieldName())->getIsRequired();
    }
}
