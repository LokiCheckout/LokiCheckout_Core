<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\StreetLinesParser;

class StreetLinesParser
{
    public function __construct(
        private $supportedCountries = ['NL', 'BE']
    ) {
    }

    public function parseStreetParts(array $streetParts, string $countryId): array
    {
        if (false === in_array($countryId, $this->supportedCountries)) {
            return $streetParts;
        }

        if (!empty($streetParts[0]) && empty($streetParts[1]) && empty($streetParts[2])) {
            $streetParts = explode(' ', $streetParts[0]);
        }

        $newStreetParts = [];
        foreach ($streetParts as $streetIndex => $streetPart) {
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

        return $newStreetParts;
    }
}
