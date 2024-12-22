<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Streetline;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldMutator;

class Mutator extends AddressFieldMutator
{

    public function save($value): void
    {
        $street = (string)$this->addressState->load('street');
        $streetLines = $this->streetLinesFactory->createFromStreet($street);
        $streetLines = $streetLines->setLineByIndex($this->index, $this->value);
        $this->addressState->save('street', $streetLines->toString());
    }
}
