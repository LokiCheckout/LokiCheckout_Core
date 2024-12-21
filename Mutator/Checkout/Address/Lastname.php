<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Checkout\Address;

use Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

class Lastname extends AddressField
{
    public function beforeSave($value): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerLastname($value);
        }
    }
}
