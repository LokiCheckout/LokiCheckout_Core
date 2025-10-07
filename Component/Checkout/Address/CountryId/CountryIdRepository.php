<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\CountryId;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldRepository;

class CountryIdRepository extends AddressFieldRepository
{
    public function saveValue(mixed $value): void
    {
        $previousValue = $this->getAddressState()->load('country_id');

        if ($previousValue !== $value) {
            $this->getAddressState()->save('street', '');
            $this->getAddressState()->save('postcode', '');
            $this->getAddressState()->save('city', '');
            $this->getAddressState()->save('region', '');
            $this->getAddressState()->save('region_id', '');
        }

        parent::saveValue($value);
    }
}
