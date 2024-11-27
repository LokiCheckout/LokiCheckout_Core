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

    public function setStreetName(string $streetName): void
    {
        $this->streetName = $streetName;
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
        return implode("\n", $this->getLines());
    }

    public function getLines(): array
    {
        return [
            $this->getStreetName(),
            $this->getHouseNumber(),
            $this->getHouseNumberAddition(),
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
