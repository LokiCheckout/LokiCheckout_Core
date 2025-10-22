<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use LokiCheckout\Core\Util\StreetLinesParser\StreetLinesParser;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\ObjectManagerInterface;

class StreetLinesFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StreetLinesParser $streetLinesParser,
    ) {
    }

    public function create(
        string $streetName,
        string $houseNumber,
        string $houseNumberAddition = '',
        string $street4 = '',
        string $countryId = '',
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
            'countryId' => $countryId,
            'maximumNumber' => $maximumNumber,
        ]);
    }

    public function createFromStreetParts(
        array $streetParts,
        string $countryId,
        ?int $maximumNumber = null
    ): StreetLines
    {
        $streetParts = $this->streetLinesParser->parseStreetParts($streetParts, $countryId);

        return $this->create(
            $streetParts[0] ?? '',
                $streetParts[1] ?? '',
                $streetParts[2] ?? '',
                $streetParts[3] ?? '',
            $countryId,
            $maximumNumber
        );
    }

    public function createFromStreet(string $street, string $countryId): StreetLines
    {
        $streetParts = explode("\n", $street);
        return $this->createFromStreetParts($streetParts, $countryId);
    }

    private function getNumberOfStreetLines(): int
    {
        return (int)$this->scopeConfig->getValue('customer/address/street_lines');
    }
}
