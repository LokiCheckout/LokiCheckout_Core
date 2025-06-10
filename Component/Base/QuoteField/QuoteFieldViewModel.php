<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\QuoteField;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

/**
 * @method CheckoutContext getContext()
 */
abstract class QuoteFieldViewModel extends CheckoutViewModel implements QuoteFieldViewModelInterface
{
    public function getFieldLabel(): string
    {
        $parentBlock = $this->getBlock();
        $fieldLabel = (string)$parentBlock->getFieldLabel();
        if ($fieldLabel !== '' && $fieldLabel !== '0') {
            return $fieldLabel;
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
