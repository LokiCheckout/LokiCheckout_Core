<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

class StreetLines
{
    public function __construct(
        private string $streetName = '',
        private string $houseNumber = '',
        private string $houseNumberAddition = '',
        private readonly string $street4 = '',
        private readonly int $maximumNumber = 2,
    ) {
    }

    public function setStreetName(string $streetName): void
    {
        $this->streetName = $streetName;
    }

    public function getStreetName(): string
    {
        switch ($this->maximumNumber) {
            case 4:
            case 3:
            case 2:
                return trim($this->streetName);
            case 1:
                return $this->concatenate([$this->streetName,$this->houseNumber,$this->houseNumberAddition,$this->street4]);
        }

        return '';
    }

    public function setHouseNumber(string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    public function getHouseNumber(): string
    {
        switch ($this->maximumNumber) {
            case 4:
            case 3:
                return trim($this->houseNumber);
            case 2:
                return $this->concatenate([$this->houseNumber, $this->houseNumberAddition, $this->street4]);
        }

        return '';
    }

    public function setHouseNumberAddition(string $houseNumberAddition): void
    {
        $this->houseNumberAddition = $houseNumberAddition;
    }

    public function getHouseNumberAddition(): string
    {
        switch ($this->maximumNumber) {
            case 4:
                return trim($this->houseNumberAddition);
            case 3:
                return $this->concatenate([$this->houseNumberAddition, $this->street4]);
        }

        return '';
    }

    public function getStreet4(): string
    {
        if ($this->maximumNumber === 4) {
            return $this->street4;
        }

        return '';
    }

    public function toString(): string
    {
        return trim(implode("\n", $this->getLines()));
    }

    public function getLines(): array
    {
        return array_slice([
            $this->getStreetName(),
            $this->getHouseNumber(),
            $this->getHouseNumberAddition(),
            $this->getStreet4(),
        ], 0, $this->maximumNumber);
    }

    public function getLineByIndex(int $index): string
    {
        $lines = $this->getLines();
        if (isset($lines[$index])) {
            return $lines[$index];
        }

        return '';
    }

    public function setLineByIndex(int $index, string $value): StreetLines
    {
        $streetName = ($index === 0) ? $value : $this->getStreetName();
        $houseNumber = ($index === 1) ? $value : $this->getHouseNumber();
        $houseNumberAddition = ($index === 2) ? $value : $this->getHouseNumberAddition();
        $street4 = ($index === 3) ? $value : $this->getStreet4();

        return new StreetLines(
            $streetName,
            $houseNumber,
            $houseNumberAddition,
            $street4,
            $this->maximumNumber
        );
    }

    private function concatenate(array $lines = []): string
    {
        $string = '';
        foreach ($lines as $line) {
            $string .= ' ' . trim($line);
        }

        return trim($string);
    }
}
