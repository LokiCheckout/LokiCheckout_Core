<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Lastname;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

class LastnameRepository extends AddressFieldRepository
{
    protected function saveValue(mixed $data): void
    {
        if ($this->getContext()->getQuote()->getCustomerIsGuest()) {
            $this->getContext()->getQuote()->setCustomerLastname($data);
        }

        parent::saveValue($data);
    }
}
