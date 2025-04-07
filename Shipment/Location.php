<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment;

use Yireo\LokiCheckout\ValueObject\Address;
use Yireo\LokiCheckout\ValueObject\Address\BusinessHours;

class Location
{
    public function __construct(
        private string $code,
        private string $label,
        private int $distance,
        private Address $address,
        private ?BusinessHours $businessHours = null,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getBusinessHours(): ?BusinessHours
    {
        return $this->businessHours;
    }
}
