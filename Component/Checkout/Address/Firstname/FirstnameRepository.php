<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Firstname;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldRepository;

class FirstnameRepository extends AddressFieldRepository
{
    protected function saveData(mixed $data): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerFirstname($data);
        }

        parent::saveData($data);
    }
}
