<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\AddressField;

use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Component\Form\Field\FieldViewModel;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

/**
 * @method AddressFieldContext getContext()
 */
abstract class AddressFieldViewModel extends FieldViewModel implements AddressFieldViewModelInterface
{
    public function getData(): mixed
    {
        return trim((string)$this->getComponent()->getRepository()->get());
    }

    // @todo: Move this into a trait or a child-class
    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if (!empty($fieldLabel)) {
            return $fieldLabel;
        }

        $attributeLabel = $this->getContext()->attributeProvider->getAttributeLabel($this->getFieldName());
        if (!empty($attributeLabel)) {
            return $attributeLabel;
        }

        return $this->getFieldName();
    }

    // @todo: Move this into a trait or a child-class
    protected function getFieldLabelFromLayout(): string
    {
        $block = $this->getBlock();
        if (false === $block instanceof Template) {
            return '';
        }

        $fieldLabel = (string)$block->getFieldLabel();
        if (empty($fieldLabel)) {
            return '';
        }

        if ($fieldLabel === '-') {
            return '';
        }

        return $fieldLabel;
    }

    public function getStep(): string
    {
        return $this->getContext()->stepProvider->getStep($this->getBlock());
    }

    public function isRequired(): bool
    {
        $required = $this->getBlock()->getData('is_required');
        if (is_bool($required)) {
            return $required;
        }

        return (bool)$this->getContext()->attributeProvider->getAttribute($this->getFieldName())->getIsRequired();
    }

    protected function getAddressState(): AddressStateInterface
    {
        return $this->getContext()->addressStateProvider->getAddressState($this->getBLock());
    }
}
