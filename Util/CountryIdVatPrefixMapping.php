<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

class CountryIdVatPrefixMapping
{
    public function getMapping(): array
    {
        return [
            'AT' => 'ATU',
            'GR' => 'EL',
            'ES' => 'ESX',
        ];
    }

    public function convert(string $countryId): string
    {
        $mapping = $this->getMapping();
        if (array_key_exists($countryId, $mapping)) {
            return $mapping[$countryId];
        }

        return $countryId;
    }
}
