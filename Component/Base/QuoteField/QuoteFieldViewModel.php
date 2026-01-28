<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\QuoteField;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\ViewModel\CheckoutState;

/**
 * @method CheckoutContext getContext()
 */
class QuoteFieldViewModel extends CheckoutViewModel implements QuoteFieldViewModelInterface
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
        $step = parent::getStep();
        if ($step !== 'none') {
            return $step;
        }

        if ($this->getContext()->getQuote()->isVirtual()) {
            return 'billing';
        }

        return 'shipping';
    }
}
