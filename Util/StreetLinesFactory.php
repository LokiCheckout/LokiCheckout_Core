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
        $newStreetParts = [];
        foreach ($streetParts as $streetIndex => $streetPart) {
            if ($streetIndex === 0 && preg_match('/^([0-9]+)/', $streetPart)) {
                $newStreetParts[] = '';
            }

            if (preg_match('/^([0-9]+)(.*)$/', $streetPart, $match)) {
                $newStreetParts[] =  $match[1];
                if (!empty($match[2])) {
                    $newStreetParts[] =  $match[2];
                }
                continue;
            }

            $newStreetParts[] = trim($streetPart);
        }

        return $this->create(
            $newStreetParts[0] ?? '',
                $newStreetParts[1] ?? '',
                $newStreetParts[2] ?? ''
        );
    }

    public function createFromStreet(string $street): StreetLines
    {
        $streetParts = explode("\n", $street);

        return $this->createFromStreetParts($streetParts);
    }
}
