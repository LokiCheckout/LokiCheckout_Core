<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\QuoteField;

use Yireo\LokiCheckout\Component\Base\Field\FieldContext;
use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

/**
 * @method FieldContext getContext()
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

        $attributeLabel = $this->getContext()->getAttributeProvider()->getAttributeLabel($this->getFieldName());
        if (!empty($attributeLabel)) {
            return $attributeLabel;
        }

        return $this->getFieldName();
    }

    public function isRequired(): bool
    {
        return (bool)$this->getContext()->getAttributeProvider()->getAttribute($this->getFieldName())->getIsRequired();
    }

    public function getStep(): string
    {
        return $this->getContext()->getStepProvider()->getStep($this->getBlock());
    }
}
