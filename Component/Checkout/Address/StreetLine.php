<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address;

use Yireo\LokiCheckout\Component\SetterInjection\InjectScopeConfig;
use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\SetterInjection\InjectStreetLinesFactory;

class StreetLine extends AddressField
{
    use InjectScopeConfig;
    use InjectStreetLinesFactory;

    protected int $index = 0;

    public function initialize(): void
    {
        parent::initialize();

        $streetLines = (int)$this->scopeConfig->getValue('customer/address/street_lines');
        if ($streetLines <= $this->index) {
            $this->allowRendering = false;
        }
    }

    protected function getSavedValue(): string|bool|int
    {
        $street = (string)$this->addressState->load('street');
        $streetLines = $this->streetLinesFactory->createFromStreet($street);
        return $streetLines->getLineByIndex($this->index);
    }


    public function isRequired(): bool
    {
        if ($this->index === 0) {
            return true;
        }

        return parent::isRequired();
    }

    public function getFieldName(): string
    {
        return 'street'.$this->index;
    }

    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if (!empty($fieldLabel)) {
            return $fieldLabel;
        }

        if ($this->index > 0) {
            return '';
        }

        return $this->getFieldName();
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
