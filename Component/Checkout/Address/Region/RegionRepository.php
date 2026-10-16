<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Region;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldRepository;

class RegionRepository extends AddressFieldRepository
{
    public function getValue(): mixed
    {
        $regionId = (string)$this->getAddressState()->get('region_id');
        if (is_numeric($regionId) && $regionId > 0) {
            return $regionId;
        }

        return (string)$this->getAddressState()->get('region');
    }

    public function saveValue(mixed $value): void
    {
        if (is_numeric($value)) {
            $this->getAddressState()->set('region_id', (string)$value);
        }

        $this->getAddressState()->set('region', (string)$value);
    }
}
