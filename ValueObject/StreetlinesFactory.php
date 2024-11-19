<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ValueObject;

use Magento\Framework\ObjectManagerInterface;

class StreetlinesFactory
{
    public function __construct(
        private ObjectManagerInterface $objectManager
    ) {
    }

    public function create(string $streetName, string $houseNumber, string $houseNumberAddition = ''): Streetlines
    {
        return $this->objectManager->create(Streetlines::class, [
            'streetName' => $streetName,
            'houseNumber' => $houseNumber,
            'houseNumberAddition' => $houseNumberAddition,
        ]);
    }

    public function createFromStreet(string $street): Streetlines
    {
        $streetParts = explode("\n", $street);
        $streetName = $streetParts[0] ?? '';
        $houseNumber = $streetParts[1] ?? '';
        $houseNumberAddition = $streetParts[2] ?? '';

        return $this->create($streetName, $houseNumber, $houseNumberAddition);
    }
}
