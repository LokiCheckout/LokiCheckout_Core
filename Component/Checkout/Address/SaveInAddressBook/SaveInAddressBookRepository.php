<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\SaveInAddressBook;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldRepository;

class SaveInAddressBookRepository extends AddressFieldRepository
{
    public function saveValue(mixed $value): void
    {
        parent::saveValue((int)$value);
    }
}
