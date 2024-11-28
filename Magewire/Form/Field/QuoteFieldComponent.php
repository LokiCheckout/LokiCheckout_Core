<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AttributeBehaviour;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectCheckoutSession;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectCustomerSession;

abstract class QuoteFieldComponent extends FieldComponent
{
    use AttributeBehaviour;
    use InjectCheckoutSession;
    use InjectCustomerSession;

    protected string $elementId = '';
    public bool $valid = true;

    public function boot(): void
    {
        $this->value = trim((string) $this->checkoutSession->getQuote()->getData($this->getFieldName()));
        if ($this->request->isPreceding() && !empty($this->value)) {
            $this->valid = $this->validate();
        }
    }

    public function getFieldLabel(): string
    {
        $parentBlock = $this->getParent();
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

    public function beforeSave($value): void
    {
    }

    public function save($value): void
    {
        $this->beforeSave($value);

        $this->checkoutSession->getQuote()->setData($this->getFieldName(), $value);
        $this->checkoutSession->getQuote()->save(); // @todo

        $this->emit('afterSaveQuote');

        $this->afterSave($value);
    }

    public function afterSave($value): void
    {
    }

    public function isRequired(): bool
    {
        return (bool)$this->getAttribute()->getIsRequired();
    }
}
