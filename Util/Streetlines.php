<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

class Streetlines
{
    public function __construct(
        private string $streetName = '',
        private string $houseNumber = '',
        private string $houseNumberAddition = '',
    ) {
    }

    public static function create(string $street): Streetlines
    {
        $streetParts = explode("\n", $street);
        $streetName = isset($streetParts[0]) ? $streetParts[0] : '';
        $houseNumber = isset($streetParts[1]) ? $streetParts[1] : '';
        $houseNumberAddition = isset($streetParts[2]) ? $streetParts[2] : '';
        return new Streetlines($streetName, $houseNumber, $houseNumberAddition);
    }

    public function setStreetName(string $streetName): Streetlines
    {
        return new Streetlines($streetName, $this->getHouseNumber(), $this->getHouseNumberAddition());
    }

    public function getStreetName(): string
    {
        return trim($this->streetName);
    }

    public function getHouseNumber(): string
    {
        return trim($this->houseNumber);
    }

    public function getHouseNumberAddition(): string
    {
        return trim($this->houseNumberAddition);
    }

    public function toString(): string
    {
        return trim(implode("\n", $this->getLines()));
    }

    public function getLines(): array
    {
        return [
            0 => $this->getStreetName(),
            1 => $this->getHouseNumber(),
            2 => $this->getHouseNumberAddition(),
        ];
    }

    public function getLineByIndex(int $index): string
    {
        $lines = $this->getLines();
        if (isset($lines[$index])) {
            return $lines[$index];
        }

        return '';
    }

    public function setLineByIndex(int $index, string $value): Streetlines
    {
        $streetName = ($index === 0) ? $value : $this->getStreetName();
        $houseNumber = ($index === 1) ? $value : $this->getHouseNumber();
        $houseNumberAddition = ($index === 2) ? $value : $this->getHouseNumberAddition();
        return new Streetlines($streetName, $houseNumber, $houseNumberAddition);
    }
}
