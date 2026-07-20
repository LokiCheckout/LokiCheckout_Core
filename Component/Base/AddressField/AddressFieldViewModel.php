<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\AddressField;

use Magento\Framework\View\Element\Template;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\ViewModel\State\AddressStateInterface;

/**
 * @method AddressFieldContext getContext()
 */
class AddressFieldViewModel extends CheckoutViewModel implements AddressFieldViewModelInterface
{
    // @todo: Move this into a trait or a child-class
    public function getFieldLabel(): string
    {
        if (false === $this->showFieldLabel()) {
            return '';
        }

        $fieldLabel = $this->getFieldLabelFromLayout();
        if ($fieldLabel !== '' && $fieldLabel !== '0') {
            return $fieldLabel;
        }

        $attributeLabel = $this->getContext()->getAttributeProvider()->getAttributeLabel($this->getFieldName());
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
        if (strlen($fieldLabel) < 1) {
            return '';
        }

        if ($fieldLabel === '-') {
            return '';
        }

        return $fieldLabel;
    }

    public function isRequired(): bool
    {
        $required = $this->getBlock()->getData('is_required');
        if (is_bool($required)) {
            return $required;
        }

        return (bool)$this->getContext()->getAttributeProvider()->getAttribute($this->getFieldName())->getIsRequired();
    }

    public function isDisabled(): bool
    {
        if ($this->isShippingAddressComponent() && $this->getContext()->getShippingAddressResolverListing()->resolves()) {
            return true;
        }

        return parent::isDisabled();
    }

    protected function isBillingAddressComponent(): bool
    {
        return (bool)stristr($this->getComponent()->getName(), '.billing.');
    }

    protected function isShippingAddressComponent(): bool
    {
        return (bool)stristr($this->getComponent()->getName(), '.shipping.');
    }

    protected function getAddressState(): AddressStateInterface
    {
        return $this->getContext()->getAddressStateProvider()->getAddressState($this->getBLock());
    }
}
