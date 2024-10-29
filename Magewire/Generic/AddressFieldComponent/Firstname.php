<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

class Firstname extends AddressFieldComponent
{
    public function beforeSave($value): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerFirstname($value);
        }
    }
}
