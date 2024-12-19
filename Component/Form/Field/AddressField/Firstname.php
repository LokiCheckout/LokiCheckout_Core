<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\AddressField;

use Yireo\LokiCheckout\Component\Form\Field\AddressField;

abstract class Firstname extends AddressField
{
    public function beforeSave($value): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerFirstname($value);
        }
    }
}
