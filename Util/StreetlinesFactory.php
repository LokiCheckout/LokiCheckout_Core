<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

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
        $street = str_replace("\n\n", "\n", $street);
        $streetParts = explode("\n", $street);

        if (count($streetParts) === 2 && preg_match('/^([0-9]+)$/', $streetParts[0])) {
            array_unshift($streetParts, '');
        }

        return $this->create(
            $streetParts[0] ?? '',
            $streetParts[1] ?? '',
            $streetParts[2] ?? ''
        );
    }
}
