<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Magento\Framework\View\Element\Template;
use Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour\AddressStateBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour\AttributeBehaviour;

abstract class AddressField extends Field implements AddressFieldInterface
{
    use AttributeBehaviour;
    use AddressStateBehaviour;

    protected function getSavedValue(): string|bool|int
    {
        return trim((string)$this->getAddressState()->load($this->getFieldName()));
    }

    // @todo: Move this into a trait or a child-class
    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if (!empty($fieldLabel)) {
            return $fieldLabel;
        }

        $attributeLabel = $this->getAttributeLabel();
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
        if ($this instanceof ShippingAddressFieldInterface) {
            return 'shipping';
        }

        if ($this instanceof BillingAddressFieldInterface) {
            return 'billing';
        }

        return 'unknown';
    }

    public function save($value): void
    {
        $this->addressState->save($this->getFieldName(), $value);
    }

    public function isRequired(): bool
    {
        $required = $this->getBlock()->getData('is_required');
        if (is_bool($required)) {
            return $required;
        }

        return (bool)$this->getAttribute()->getIsRequired();
    }

    public function getTargets(): array
    {
        return [
            ...parent::getTargets(),
            'loki-checkout.sidebar',
        ];
    }
}
