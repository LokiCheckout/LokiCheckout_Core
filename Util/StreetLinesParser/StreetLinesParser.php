<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\StreetLinesParser;

use LokiCheckout\Core\Exception\StreetLineParserException;

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
            try {
                $newStreetParts = $this->parseSingleStreetLine($streetParts[0]);
                if (!empty($newStreetParts)) {
                    return $newStreetParts;
                }
            } catch (StreetLineParserException $e) {}
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

    /**
     * @throws StreetLineParserException
     */
    private function parseSingleStreetLine(string $streetLine): array
    {
        $trimmedLine = trim($streetLine);
        if (preg_match('/^(\d+)([a-zA-Z]{1,4})(.*)$/', $trimmedLine, $matches)) {
            throw new StreetLineParserException();
        }

        if (!preg_match('/^(.*?)\s+(\d+)(.*)$/', $trimmedLine, $matches)) {
            return [$trimmedLine];
        }

        $streetName = trim($matches[1]);
        $houseNumber = $matches[2];
        $addition = trim($matches[3]);

        $result = [];
        if ($streetName !== '') {
            $result[] = $streetName;
        }
        $result[] = $houseNumber;

        if ($addition !== '') {
            $additionParts = preg_split('/\s+/', $addition, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($additionParts as $part) {
                $result[] = $part;
            }
        }

        return $result;
    }
}
