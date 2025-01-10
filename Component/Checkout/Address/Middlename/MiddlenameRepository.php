<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Middlename;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

class MiddlenameRepository extends AddressFieldRepository
{
    public function saveValue(mixed $value): void
    {
        if ($this->getContext()->getQuote()->getCustomerIsGuest()) {
            $this->getContext()->getQuote()->setCustomerMiddlename($data);
        }

        parent::saveValue($data);
    }
}
