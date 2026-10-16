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
            $streetPart = trim((string)$streetPart, " \f\n\r\t\v\x00");

            // A first line is only a house number when it contains nothing else (like "126D"),
            // otherwise it is a street name starting with a digit (like "1e Jan Steenstraat")
            if ($streetIndex === 0) {
                if (false === (bool)preg_match('/^\d+\s*[a-zA-Z]{0,4}$/', $streetPart)) {
                    $newStreetParts[] = $streetPart;
                    continue;
                }

                $newStreetParts[] = '';
            }

            if (preg_match('/^(\d+)(.*)$/', $streetPart, $match)) {
                $newStreetParts[] = $match[1];
                $addition = trim($match[2], " \f\n\r\t\v\x00");
                if ($addition !== '' && $addition !== '0') {
                    $newStreetParts[] = $addition;
                }
                continue;
            }

            $newStreetParts[] = trim($streetPart, " \f\n\r\t\v\x00");
        }

        return $newStreetParts;
    }

    /**
     * @throws StreetLineParserException
     */
    private function parseSingleStreetLine(string $streetLine): array
    {
        $trimmedLine = trim($streetLine, " \f\n\r\t\v\x00");
        if (is_numeric($trimmedLine)) {
            return ['', $trimmedLine];
        }

        if (preg_match('/^(\d+)([a-zA-Z]{1,4})$/', $trimmedLine, $matches)) {
            throw new StreetLineParserException();
        }

        if (!preg_match('/^(.*?)\s+(\d+)(.*)$/', $trimmedLine, $matches)) {
            return [$trimmedLine];
        }

        $streetName = trim($matches[1], " \f\n\r\t\v\x00");
        $houseNumber = $matches[2];
        $addition = trim($matches[3], " \f\n\r\t\v\x00");

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
