<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Streetline;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldRepository;

/**
 * @method StreetLineContext getContext()
 */
class StreetLineRepository extends AddressFieldRepository
{
    protected function getData(): mixed
    {
        $street = (string)$this->getAddressState()->load('street');
        $streetLines = $this->getContext()->streetLinesFactory->createFromStreet($street);
        return $streetLines->getLineByIndex($this->index);
    }

    protected function saveData(mixed $data): void
    {
        $street = (string)$this->addressState->load('street');
        $streetLines = $this->getContext()->streetLinesFactory->createFromStreet($street);
        $streetLines = $streetLines->setLineByIndex($this->getIndex(), $data);
        $this->addressState->save('street', $streetLines->toString());
    }

    private function getIndex(): int
    {
        $fieldName = $this->getFieldName(); // @todo: Add a regex to extract index from field name
        return 42;
    }
}
