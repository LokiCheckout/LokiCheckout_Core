<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Firstname;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldRepository;

class FirstnameRepository extends AddressFieldRepository
{
    public function saveValue(mixed $value): void
    {
        if ($this->getContext()->getCheckoutState()->getQuote()->getCustomerIsGuest()) {
            $this->getContext()->getCheckoutState()->getQuote()->setCustomerFirstname($value);
        }

        parent::saveValue($value);
    }
}
