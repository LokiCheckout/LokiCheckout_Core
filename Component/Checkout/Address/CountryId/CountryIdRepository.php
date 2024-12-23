<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\CountryId;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldRepository;

class CountryIdRepository extends AddressFieldRepository
{
    protected function saveData(mixed $data): void
    {
        $this->getAddressState()->save('region', '');
        $this->getAddressState()->save('region_id', '');
        parent::saveData($data);
    }
}
