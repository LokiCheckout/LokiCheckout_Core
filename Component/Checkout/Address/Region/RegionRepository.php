<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Region;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldRepository;

class RegionRepository extends AddressFieldRepository
{
    protected function getValue(): mixed
    {
        $regionId = (string)$this->getAddressState()->load('region_id');
        if (is_numeric($regionId) && $regionId > 0) {
            return $regionId;
        }

        return (string)$this->getAddressState()->load('region');
    }

    protected function saveValue(mixed $data): void
    {
        if (is_numeric($data)) {
            $this->getAddressState()->save('region_id', $data);
        }

        $this->getAddressState()->save('region', $data);
    }
}
