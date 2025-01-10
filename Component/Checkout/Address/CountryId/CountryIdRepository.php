<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\CountryId;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

class CountryIdRepository extends AddressFieldRepository
{
    public function saveValue(mixed $value): void
    {
        $this->getAddressState()->save('region', '');
        $this->getAddressState()->save('region_id', '');
        parent::saveValue($value);
    }
}
