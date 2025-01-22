<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Lastname;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

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
