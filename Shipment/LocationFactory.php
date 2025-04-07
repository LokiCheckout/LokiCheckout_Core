<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment;

use Magento\Framework\ObjectManagerInterface;
use Yireo\LokiCheckout\ValueObject\Address;
use Yireo\LokiCheckout\ValueObject\Address\BusinessHours;

class LocationFactory
{
    public function __construct(
        private ObjectManagerInterface $objectManager
    ) {
    }

    public function create(
        string $code,
        string $label,
        int $distance,
        Address $address,
        ?BusinessHours $businessHours = null,
    ): Location
    {
        return $this->objectManager->create(Location::class, [
            'code' => $code,
            'label' => $label,
            'distance' => $distance,
            'address' => $address,
            'businessHours' => $businessHours,
        ]);
    }
}
