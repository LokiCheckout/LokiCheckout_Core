<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Lastname;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldRepository;

class LastnameRepository extends AddressFieldRepository
{
    protected function saveData(mixed $data): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerLastname($data);
        }

        parent::saveData($data);
    }
}
