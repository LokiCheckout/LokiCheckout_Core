<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\StreetLine;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

/**
 * @method StreetLineContext getContext()
 */
class StreetLineRepository extends AddressFieldRepository
{
    protected function getData(): mixed
    {
        $street = (string)$this->getAddressState()->load('street');
        $streetLines = $this->getContext()->streetLinesFactory->createFromStreet($street);
        return $streetLines->getLineByIndex($this->getIndex());
    }

    protected function saveData(mixed $data): void
    {
        $street = (string)$this->getAddressState()->load('street');
        $streetLines = $this->getContext()->streetLinesFactory->createFromStreet($street);
        $streetLines = $streetLines->setLineByIndex($this->getIndex(), $data);
        $this->getAddressState()->save('street', $streetLines->toString());
    }

    private function getIndex(): int
    {
        return (int)str_replace('street', '', $this->getFieldName());
    }
}
