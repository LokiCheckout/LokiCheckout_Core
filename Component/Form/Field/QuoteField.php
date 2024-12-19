<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour\AttributeBehaviour;
use Yireo\LokiCheckout\Component\SetterInjection\InjectCheckoutSession;
use Yireo\LokiCheckout\Component\SetterInjection\InjectCustomerSession;

abstract class QuoteField extends Field
{
    use AttributeBehaviour;
    use InjectCheckoutSession;
    use InjectCustomerSession;

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
