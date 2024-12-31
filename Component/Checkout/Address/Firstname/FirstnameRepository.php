<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Firstname;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

class FirstnameRepository extends AddressFieldRepository
{
    protected function saveData(mixed $data): void
    {
        if ($this->getContext()->getQuote()->getCustomerIsGuest()) {
            $this->getContext()->getQuote()->setCustomerFirstname($data);
        }

        parent::saveData($data);
    }
}
