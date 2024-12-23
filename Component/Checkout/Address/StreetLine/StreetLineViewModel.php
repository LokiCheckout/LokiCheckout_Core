<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\StreetLine;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldViewModel;

class StreetLineViewModel extends AddressFieldViewModel
{
    public function isAllowRendering(): bool
    {
        $streetLines = (int)$this->getContext()->scopeConfig->getValue('customer/address/street_lines');
        if ($streetLines <= $this->getIndex()) {
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

    public function getFieldName(): string
    {
        return 'street'.$this->getIndex();
    }

    public function getFieldLabel(): string
    {
        $fieldLabel = $this->getFieldLabelFromLayout();
        if (!empty($fieldLabel)) {
            return $fieldLabel;
        }

        if ($this->getIndex() > 0) {
            return '';
        }

        return $this->getFieldName();
    }

    private function getIndex(): int
    {
        $fieldName = $this->getFieldName(); // @todo: Add a regex to extract index from field name
        return 42;
    }
}
