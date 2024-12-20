<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

use Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

abstract class Firstname extends AddressField
{
    public function beforeSave($value): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerFirstname($value);
        }
    }
}
