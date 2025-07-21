<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Lastname;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldRepository;

class LastnameRepository extends AddressFieldRepository
{
    public function saveValue(mixed $value): void
    {
        if ($this->getContext()->getCheckoutState()->getQuote()->getCustomerIsGuest()) {
            $this->getContext()->getCheckoutState()->getQuote()->setCustomerLastname($value);
        }

        parent::saveValue($value);
    }
}
