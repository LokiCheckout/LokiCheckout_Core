<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\ObjectManagerInterface;

class StreetLinesFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function create(
        string $streetName,
        string $houseNumber,
        string $houseNumberAddition = '',
        string $street4 = '',
        ?int $maximumNumber = null
    ): StreetLines {
        if ($maximumNumber === null) {
            $maximumNumber = $this->getNumberOfStreetLines();
        }

        return $this->objectManager->create(StreetLines::class, [
            'streetName' => $streetName,
            'houseNumber' => $houseNumber,
            'houseNumberAddition' => $houseNumberAddition,
            'street4' => $street4,
            'maximumNumber' => $maximumNumber,
        ]);
    }

    public function createFromStreetParts(array $streetParts, ?int $maximumNumber = null): StreetLines
    {
        if (!empty($streetParts[0]) && empty($streetParts[1]) && empty($streetParts[2])) {
            $streetParts = explode(' ', $streetParts[0]);
        }

        $newStreetParts = [];
        foreach ($streetParts as $streetIndex => $streetPart) {
            // Street starts with a house number but without a streetname
            if ($streetIndex === 0 && preg_match('/^(\d+)/', $streetPart)) {
                $newStreetParts[] = '';
            }

            if (preg_match('/^(\d+)(.*)$/', $streetPart, $match)) {
                $newStreetParts[] = $match[1];
                if (isset($match[2]) && ($match[2] !== '' && $match[2] !== '0')) {
                    $newStreetParts[] = $match[2];
                }
                continue;
            }

            $newStreetParts[] = trim($streetPart);
        }

        return $this->create(
            $newStreetParts[0] ?? '',
            $newStreetParts[1] ?? '',
            $newStreetParts[2] ?? '',
            $newStreetParts[3] ?? '',
                $maximumNumber
        );
    }

    public function createFromStreet(string $street): StreetLines
    {
        $streetParts = explode("\n", $street);
        if (count($streetParts) === 1) {
            $streetParts = explode(" ", $street);
        }

        return $this->createFromStreetParts($streetParts);
    }

    private function getNumberOfStreetLines(): int
    {
        return (int)$this->scopeConfig->getValue('customer/address/street_lines');
    }
}
