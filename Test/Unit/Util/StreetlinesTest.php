<?php

namespace Yireo\LokiCheckout\Test\Unit\Util;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Util\Streetlines;

class StreetlinesTest extends TestCase
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
        $streetlines = new Streetlines($streetName, $houseNumber, $houseNumberAddition);
        $this->assertSame($streetName, $streetlines->getStreetName());
        $this->assertSame($houseNumber, $streetlines->getHouseNumber());
        $this->assertSame($houseNumberAddition, $streetlines->getHouseNumberAddition());
    }

    /**
     * @return void
     * @dataProvider getTestArguments
     */
    public function testGetLines(string $street, string $streetName, string $houseNumber, string $houseNumberAddition)
    {
        $streetlines = new Streetlines($streetName, $houseNumber, $houseNumberAddition);
        $lines = $streetlines->getLines();
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
        $streetlines = new Streetlines($streetName, $houseNumber, $houseNumberAddition);
        $this->assertSame($street, $streetlines->toString());
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
