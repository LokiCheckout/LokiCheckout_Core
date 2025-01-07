<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Firstname;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

class FirstnameRepository extends AddressFieldRepository
{
    public function saveValue(mixed $data): void
    {
        if ($this->getContext()->getQuote()->getCustomerIsGuest()) {
            $this->getContext()->getQuote()->setCustomerFirstname($data);
        }

        parent::saveValue($data);
    }
}
