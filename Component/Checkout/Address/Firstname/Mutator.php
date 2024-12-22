<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Firstname;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldMutator;

class Mutator extends AddressFieldMutator
{
    public function beforeSave($value): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerFirstname($value);
        }
    }
}
