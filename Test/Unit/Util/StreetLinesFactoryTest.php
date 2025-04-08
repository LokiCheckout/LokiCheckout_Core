<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Util;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Test\Unit\Stub\SimpleObjectManager;
use Yireo\LokiCheckout\Util\StreetLinesFactory;

class StreetLinesFactoryTest extends TestCase
{
    public function testCreate()
    {
        $objectManager = new SimpleObjectManager;
        $scopeConfig = $this->getScopeConfig(4);
        $streetLinesFactory = new StreetLinesFactory($objectManager, $scopeConfig);

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
     * @param int $numberOfStreetlines
     * @return void
     * @dataProvider getCreateFromStreetInput
     */
    public function testCreateFromStreet(
        string $street,
        string $streetName,
        string $houseNumber,
        string $houseNumberAddition,
        ?string $street4 = null,
        int $numberOfStreetlines = 2
    ) {
        $objectManager = new SimpleObjectManager;
        $scopeConfig = $this->getScopeConfig($numberOfStreetlines);
        $streetLinesFactory = new StreetLinesFactory($objectManager, $scopeConfig);

        $streetLines = $streetLinesFactory->createFromStreet($street);

        if ($numberOfStreetlines == 1) {
            $this->assertSame(trim(implode(' ', [$streetName, $houseNumber, $houseNumberAddition, $street4])), $streetLines->getStreetName());
            $this->assertEmpty($streetLines->getHouseNumber());
            $this->assertEmpty($streetLines->getHouseNumberAddition());
            $this->assertEmpty($streetLines->getStreet4());
        }

        if ($numberOfStreetlines == 2) {
            $this->assertSame($streetName, $streetLines->getStreetName());
            $this->assertSame(trim(implode(' ', [$houseNumber, $houseNumberAddition, $street4])), $streetLines->getHouseNumber());
            $this->assertEmpty($streetLines->getHouseNumberAddition());
            $this->assertEmpty($streetLines->getStreet4());
        }

        if ($numberOfStreetlines == 3) {
            $this->assertSame($streetName, $streetLines->getStreetName());
            $this->assertSame($houseNumber, $streetLines->getHouseNumber());
            $this->assertSame(trim(implode(' ', [$houseNumberAddition, $street4])), $streetLines->getHouseNumberAddition());
            $this->assertEmpty($streetLines->getStreet4());
        }

        if ($numberOfStreetlines == 4) {
            $this->assertSame($streetName, $streetLines->getStreetName());
            $this->assertSame($houseNumber, $streetLines->getHouseNumber());
            $this->assertSame($houseNumberAddition, $streetLines->getHouseNumberAddition());
            $this->assertSame($street4, $streetLines->getStreet4());
        }
    }

    public function getCreateFromStreetInput(): array
    {
        return [
            ["Example 1 A B", 'Example', '1', 'A', 'B', 4],
            ["Example\n1\nA\nB", 'Example', '1', 'A', 'B', 4],
            ["Example\n1\nA", 'Example', '1', 'A', null, 3],
            ["Example\n1\nA B", 'Example', '1', 'A', 'B', 3],
            ["Example\n1 A", 'Example', '1', 'A', null, 2],
            ["Example 1 A", 'Example', '1', 'A', null, 1],
            ["\n1\nA", '', '1', 'A', null, 3],
            ["\n\nA", '', '', 'A', null, 3],
            [" \n \nA", '', '', 'A', null, 3],
            [" \n \nA\n\n\n", '', '', 'A', null, 3],
            ["12AA", '', '12', 'AA', null, 3],
            ["12\nAA", '', '12', 'AA', null, 3],
        ];
    }

    private function getScopeConfig(int $numberOfStreetlines = 2): ScopeConfigInterface
    {
        $mockup = $this->createMock(ScopeConfigInterface::class);
        $mockup->method('getValue')->willReturn($numberOfStreetlines);

        return $mockup;
    }
}
