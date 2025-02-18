<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Util;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Test\Unit\Stub\SimpleObjectManager;
use Yireo\LokiCheckout\Util\StreetLinesFactory;

class StreetLinesFactoryTest extends TestCase
{
    public function testCreate()
    {
        $objectManager = new SimpleObjectManager;
        $streetLinesFactory = new StreetLinesFactory($objectManager);

        $streetLines = $streetLinesFactory->create('Example', '1', 'A');
        $this->assertSame('Example', $streetLines->getStreetName());
        $this->assertSame('1', $streetLines->getHouseNumber());
        $this->assertSame('A', $streetLines->getHouseNumberAddition());
    }

    /**
     * @param string $street
     * @param string $streetName
     * @param string $streetNumber
     * @param string $streetNumberAddition
     * @return void
     * @dataProvider getCreateFromStreetInput
     */
    public function testCreateFromStreet(string $street, string $streetName, string $streetNumber, string $streetNumberAddition)
    {
        $objectManager = new SimpleObjectManager;
        $streetLinesFactory = new StreetLinesFactory($objectManager);

        $streetLines = $streetLinesFactory->createFromStreet($street);
        $this->assertSame($streetName, $streetLines->getStreetName());
        $this->assertSame($streetNumber, $streetLines->getHouseNumber());
        $this->assertSame($streetNumberAddition, $streetLines->getHouseNumberAddition(), var_export($streetLines->getLines(), true));
    }

    public function getCreateFromStreetInput(): array
    {
        return [
            ["Example\n1\nA", 'Example', '1', 'A'],
            ["\n1\nA", '', '1', 'A'],
            ["\n\nA", '', '', 'A'],
            [" \n \nA", '', '', 'A'],
            [" \n \nA\n\n\n", '', '', 'A'],
            ["12AA", '', '12', 'AA'],
            ["12\nAA", '', '12', 'AA'],
        ];
    }
}
