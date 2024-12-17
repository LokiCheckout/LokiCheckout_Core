<?php

namespace Yireo\LokiCheckout\Test\Unit\Util;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Util\StreetLines;

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
        string $houseNumberAddition
    ) {
        $streetLines = new StreetLines($streetName, $houseNumber, $houseNumberAddition);
        $this->assertSame($streetName, $streetLines->getStreetName());
        $this->assertSame($houseNumber, $streetLines->getHouseNumber());
        $this->assertSame($houseNumberAddition, $streetLines->getHouseNumberAddition());
    }

    /**
     * @return void
     * @dataProvider getTestArguments
     */
    public function testGetLines(string $street, string $streetName, string $houseNumber, string $houseNumberAddition)
    {
        $streetLines = new StreetLines($streetName, $houseNumber, $houseNumberAddition);
        $lines = $streetLines->getLines();
        $this->assertSame($streetName, $lines[0], var_export($lines, true));
        $this->assertSame($houseNumber, $lines[1], var_export($lines, true));
        $this->assertSame($houseNumberAddition, $lines[2], var_export($lines, true));
    }

    /**
     * @return void
     * @dataProvider getTestArguments
     */
    public function testToString(string $street, string $streetName, string $houseNumber, string $houseNumberAddition)
    {
        $streetLines = new StreetLines($streetName, $houseNumber, $houseNumberAddition);
        $this->assertSame($street, $streetLines->toString());
    }

    public function getTestArguments(): array
    {
        return [
            ["Example\n1\nA", 'Example', '1', 'A'],
            ["Example\n\nA", 'Example', '', 'A'],
            ["\n\nA", '', '', 'A'],
        ];
    }
}
