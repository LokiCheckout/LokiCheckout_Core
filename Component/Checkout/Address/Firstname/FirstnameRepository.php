<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Firstname;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

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
