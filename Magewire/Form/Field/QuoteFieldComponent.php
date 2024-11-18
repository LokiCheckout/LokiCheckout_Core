<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour\AttributeBehaviour;

abstract class QuoteFieldComponent extends FieldComponent
{
    use AttributeBehaviour;

    protected string $elementId = '';
    public string $value = '';
    public bool $valid = true;

    public function __construct(
        protected CheckoutSession $checkoutSession,
        protected CustomerSession $customerSession
    ) {
    }

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

        $this->emit('quoteSave');

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
