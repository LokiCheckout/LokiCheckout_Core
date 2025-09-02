<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\StreetLine;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldViewModel;

class StreetLineViewModel extends AddressFieldViewModel
{
    public function isAllowRendering(): bool
    {
        $streetLines = (int)$this->getContext()->getScopeConfig()->getValue('customer/address/street_lines');

        if ($this->getIndex() >= $streetLines) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function isRequired(): bool
    {
        if ($this->getIndex() === 0) {
            return true;
        }

        return parent::isRequired();
    }

    public function showFieldLabel(): bool
    {
        if (strlen($this->getFieldLabel()) < 1 && $this->getIndex() > 0) {
            return false;
        }

        return parent::showFieldLabel();
    }

    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if (strlen($fieldLabel) > 0) {
            return $fieldLabel;
        }

        return ucfirst($this->getFieldName());
    }

    private function getIndex(): int
    {
        $fieldName = $this->getFieldName();
        return (int)str_replace('street', '', $fieldName);
    }
}
