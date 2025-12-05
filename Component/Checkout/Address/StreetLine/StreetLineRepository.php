<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\StreetLine;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldRepository;

/**
 * @method StreetLineContext getContext()
 */
class StreetLineRepository extends AddressFieldRepository
{
    public function getValue(): mixed
    {
        $numberOfStreetLines = (int)$this->getContext()->getScopeConfig()->getValue('customer/address/street_lines');

        $street = (string)$this->getAddressState()->get('street');
        $countryId = (string)$this->getAddressState()->get('country_id');
        $streetLines = $this->getContext()->getStreetLinesFactory()->createFromStreet($street, $countryId);
        if ($this->getIndex() + 1 < $numberOfStreetLines) {
            return $streetLines->getLineByIndex($this->getIndex());
        }

        return $streetLines->getLineByIndex($this->getIndex())
            . $streetLines->getLineByIndex($this->getIndex() + 1)
            . $streetLines->getLineByIndex($this->getIndex() + 2)
            . $streetLines->getLineByIndex($this->getIndex() + 3);
    }

    public function saveValue(mixed $value): void
    {
        $street = (string)$this->getAddressState()->get('street');
        $countryId = (string)$this->getAddressState()->get('country_id');
        $streetLines = $this->getContext()->getStreetLinesFactory()->createFromStreet($street, $countryId);
        $streetLines = $streetLines->setLineByIndex($this->getIndex(), $value);
        $this->getAddressState()->set('street', $streetLines->toString());
    }

    private function getIndex(): int
    {
        return (int)str_replace('street', '', $this->getFieldName());
    }
}
