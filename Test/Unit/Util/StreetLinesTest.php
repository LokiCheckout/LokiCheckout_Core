<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Util;

use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Util\StreetLines;

class StreetLinesTest extends TestCase
{
    /**
     * @return void
     * @dataProvider getTestArguments
     */
    public function testInstantiation(
        string $street,
        string $streetName,
        string $houseNumber,
        string $houseNumberAddition,
        string $countryId
    ) {
        $streetLines = new StreetLines(
            $streetName,
            $houseNumber,
            $houseNumberAddition,
            '',
            $countryId,
            3
        );

        $this->assertSame($streetName, $streetLines->getStreetName());
        $this->assertSame($houseNumber, $streetLines->getHouseNumber());
        $this->assertSame($houseNumberAddition, $streetLines->getHouseNumberAddition());
    }

    /**
     * @return void
     * @dataProvider getTestArguments
     */
    public function testGetLines(
        string $street,
        string $streetName,
        string $houseNumber,
        string $houseNumberAddition,
        string $countryId
    ) {
        $streetLines = new StreetLines(
            $streetName,
            $houseNumber,
            $houseNumberAddition,
            '',
            $countryId,
            3
        );

        $lines = $streetLines->getLines();
        $this->assertSame($streetName, $lines[0], var_export($lines, true));
        $this->assertSame($houseNumber, $lines[1], var_export($lines, true));
        $this->assertSame($houseNumberAddition, $lines[2], var_export($lines, true));
    }

    /**
     * @return void
     * @dataProvider getTestArguments
     */
    public function testToString(
        string $street,
        string $streetName,
        string $houseNumber,
        string $houseNumberAddition,
        string $countryId
    ) {
        $streetLines = new StreetLines(
            $streetName,
            $houseNumber,
            $houseNumberAddition,
            '',
            $countryId,
            3
        );
        $this->assertSame($street, $streetLines->toString());
    }

    public function getTestArguments(): array
    {
        return [
            ["Example\n1\nA", 'Example', '1', 'A', 'NL'],
            ["Example Street\n1\nA", 'Example Street', '1', 'A', 'NL'],
            ["Example  Street\n1\nA", 'Example  Street', '1', 'A', 'NL'],
            ["Example\n\nA", 'Example', '', 'A', 'NL'],
            ["A", '', '', 'A', 'NL'],
            ["200 Example\nApartment 1", '200 Example', 'Apartment 1', '', 'US'],
        ];
    }
}
