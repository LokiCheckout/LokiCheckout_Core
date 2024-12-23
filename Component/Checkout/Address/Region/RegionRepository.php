<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Region;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldRepository;

class RegionRepository extends AddressFieldRepository
{
    protected function saveData(mixed $data): void
    {
        if (is_numeric($data)) {
            $this->getAddressState()->save('region_id', $data);
        }

        parent::saveData($data);
    }
}
