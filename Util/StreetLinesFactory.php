<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\ObjectManagerInterface;

class StreetLinesFactory
{
    public function __construct(
        private ObjectManagerInterface $objectManager,
    ) {
    }

    public function create(string $streetName, string $houseNumber, string $houseNumberAddition = ''): StreetLines
    {
        return $this->objectManager->create(StreetLines::class, [
            'streetName' => $streetName,
            'houseNumber' => $houseNumber,
            'houseNumberAddition' => $houseNumberAddition,
        ]);
    }

    public function createFromStreetParts(array $streetParts): StreetLines
    {
        if (count($streetParts) === 2 && preg_match('/^([0-9]+)$/', $streetParts[0])) {
            array_unshift($streetParts, '');
        }

        return $this->create(
            $streetParts[0] ?? '',
            $streetParts[1] ?? '',
            $streetParts[2] ?? ''
        );
    }

    public function createFromStreet(string $street): StreetLines
    {
        $streetParts = explode("\n", $street);

        return $this->createFromStreetParts($streetParts);
    }
}
