<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\StreetLine;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

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

    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if ($fieldLabel !== '' && $fieldLabel !== '0') {
            return $fieldLabel;
        }

        if ($this->getIndex() > 0) {
            return '';
        }

        return $this->getFieldName();
    }

    private function getIndex(): int
    {
        $fieldName = $this->getFieldName();
        return (int)str_replace('street', '', $fieldName);
    }
}
