<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\StreetLine;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

/**
 * @method StreetLineContext getContext()
 */
class StreetLineRepository extends AddressFieldRepository
{
    public function getValue(): mixed
    {
        $street = (string)$this->getAddressState()->load('street');
        $streetLines = $this->getContext()->getStreetLinesFactory()->createFromStreet($street);
        return $streetLines->getLineByIndex($this->getIndex());
    }

    public function saveValue(mixed $data): void
    {
        $street = (string)$this->getAddressState()->load('street');
        $streetLines = $this->getContext()->getStreetLinesFactory()->createFromStreet($street);
        $streetLines = $streetLines->setLineByIndex($this->getIndex(), $data);
        $this->getAddressState()->save('street', $streetLines->toString());
    }

    private function getIndex(): int
    {
        return (int)str_replace('street', '', $this->getFieldName());
    }
}
