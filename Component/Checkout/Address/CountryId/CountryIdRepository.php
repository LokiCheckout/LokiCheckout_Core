<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\CountryId;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldRepository;

class CountryIdRepository extends AddressFieldRepository
{
    public function getPriority(): int
    {
        return -1;
    }
}
