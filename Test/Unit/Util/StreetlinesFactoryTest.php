<?php

namespace Yireo\LokiCheckout\Test\Unit\Util;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Test\Unit\Stub\SimpleObjectManager;
use Yireo\LokiCheckout\Util\StreetlinesFactory;

class StreetlinesFactoryTest extends TestCase
{
    public function testCreate()
    {
        $objectManager = new SimpleObjectManager;
        $streetlinesFactory = new StreetlinesFactory($objectManager);

        $streetlines = $streetlinesFactory->create('Example Street', '1', 'A');
        $this->assertSame('Example Street', $streetlines->getStreetName());
        $this->assertSame('1', $streetlines->getHouseNumber());
        $this->assertSame('A', $streetlines->getHouseNumberAddition());
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
        $streetlinesFactory = new StreetlinesFactory($objectManager);

        $streetlines = $streetlinesFactory->createFromStreet($street);
        $this->assertSame($streetName, $streetlines->getStreetName());
        $this->assertSame($streetNumber, $streetlines->getHouseNumber());
        $this->assertSame($streetNumberAddition, $streetlines->getHouseNumberAddition());
    }

    public function getCreateFromStreetInput(): array
    {
        return [
            ["Example Street\n1\nA", 'Example Street', '1', 'A'],
            ["\n1\nA", '', '1', 'A'],
            ["\n\nA", '', '', 'A'],
            [" \n \nA", '', '', 'A'],
            [" \n \nA\n\n\n", '', '', 'A'],
        ];
    }
}
