<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Util;

use LokiCheckout\Core\Util\StreetLinesParser\StreetLinesParser;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Test\Unit\Stub\SimpleObjectManager;
use LokiCheckout\Core\Util\StreetLinesFactory;

class StreetLinesFactoryTest extends TestCase
{
    public function testCreate()
    {
        $objectManager = new SimpleObjectManager();
        $streetLinesParser = new StreetLinesParser();
        $scopeConfig = $this->getScopeConfig(4);
        $streetLinesFactory = new StreetLinesFactory($objectManager, $scopeConfig, $streetLinesParser);

        $streetLines = $streetLinesFactory->create(
            'Example',
            '1',
            'A',
            countryId: 'NL'
        );

        $this->assertSame('Example', $streetLines->getStreetName());
        $this->assertSame('1', $streetLines->getHouseNumber());
        $this->assertSame('A', $streetLines->getHouseNumberAddition());
    }

    /**
     * @dataProvider getCreateFromStreetInput
     */
    public function testCreateFromStreet(
        string $street,
        string $expectedStreetName,
        string $expectedHouseNumber,
        string $expectedHouseNumberAddition,
        ?string $street4 = null,
        string $countryId = '',
        int $numberOfStreetlines = 2
    ) {
        $objectManager = new SimpleObjectManager();
        $scopeConfig = $this->getScopeConfig($numberOfStreetlines);
        $streetLinesParser = new StreetLinesParser();
        $streetLinesFactory = new StreetLinesFactory($objectManager, $scopeConfig, $streetLinesParser);

        $streetLines = $streetLinesFactory->createFromStreet($street, $countryId);

        if ($numberOfStreetlines == 1) {
            $this->assertSame(
                trim(implode(' ', [$expectedStreetName, $expectedHouseNumber, $expectedHouseNumberAddition, $street4]), " \f\n\r\t\v\x00"),
                $streetLines->getStreetName()
            );
            $this->assertEmpty($streetLines->getHouseNumber());
            $this->assertEmpty($streetLines->getHouseNumberAddition());
            $this->assertEmpty($streetLines->getStreet4());
        }

        if ($numberOfStreetlines == 2) {
            $this->assertSame($expectedStreetName, $streetLines->getStreetName());
            $this->assertSame(
                trim(implode(' ', [$expectedHouseNumber, $expectedHouseNumberAddition, $street4]), " \f\n\r\t\v\x00"),
                $streetLines->getHouseNumber()
            );

            $this->assertEmpty($streetLines->getHouseNumberAddition());
            $this->assertEmpty($streetLines->getStreet4());
        }

        if ($numberOfStreetlines == 3) {
            $this->assertSame($expectedStreetName, $streetLines->getStreetName());
            $this->assertSame($expectedHouseNumber, $streetLines->getHouseNumber());
            $this->assertSame(
                trim(implode(' ', [$expectedHouseNumberAddition, $street4]), " \f\n\r\t\v\x00"),
                $streetLines->getHouseNumberAddition()
            );
            $this->assertEmpty($streetLines->getStreet4());
        }

        if ($numberOfStreetlines == 4) {
            $this->assertSame($expectedStreetName, $streetLines->getStreetName());
            $this->assertSame($expectedHouseNumber, $streetLines->getHouseNumber());
            $this->assertSame($expectedHouseNumberAddition, $streetLines->getHouseNumberAddition());
            $this->assertSame($street4, $streetLines->getStreet4());
        }
    }

    public static function getCreateFromStreetInput(): array
    {
        return [
            /*["Example 1 A B", 'Example', '1', 'A', 'B', 'NL', 4],
            ["Example\n1\nA\nB", 'Example', '1', 'A', 'B', 'NL', 4],
            ["Example Street\n1\nA\nB", 'Example Street', '1', 'A', 'B', 'NL', 4],
            ["Example\n1\nA", 'Example', '1', 'A', null, 'NL', 3],
            ["Example\n1\nA B", 'Example', '1', 'A', 'B', 'NL', 3],
            ["Example\n1 A", 'Example', '1', 'A', null, 'NL', 2],
            ["Example 1 A", 'Example', '1', 'A', null, 'NL', 1],
            ["\n1\nA", '', '1', 'A', null, 'NL', 3],
            ["\n\nA", '', '', 'A', null, 'NL', 3],
            [" \n \nA", '', '', 'A', null, 'NL', 3],
            [" \n \nA\n\n\n", '', '', 'A', null, 'NL', 3],*/
            ["12", '', '12', '', null, 'NL', 3],
            /*["12AA", '', '12', 'AA', null, 'NL', 3],
            ["12\nAA", '', '12', 'AA', null, 'NL', 3],
            ["12 Example Street", '12 Example Street', '', '', null, 'US', 2],
            ["12 Example\nApartment 1", '12 Example', 'Apartment 1', '', null, 'US', 2],*/
        ];
    }

    /**
     * @param string $expectedStreet
     * @param array $streetParts
     * @param int $numberOfStreetlines
     *
     * @return void
     * @dataProvider getCreateFromStreetPartsInput
     */
    public function testCreateFromStreetParts(
        string $expectedStreet,
        array $streetParts,
        string $countryId,
        int $numberOfStreetlines = 2
    ) {
        $objectManager = new SimpleObjectManager();
        $scopeConfig = $this->getScopeConfig($numberOfStreetlines);
        $streetLinesParser = new StreetLinesParser();
        $streetLinesFactory = new StreetLinesFactory($objectManager, $scopeConfig, $streetLinesParser);

        $streetLines = $streetLinesFactory->createFromStreetParts($streetParts, $countryId);
        $this->assertEquals($expectedStreet, $streetLines->toString());
    }

    public static function getCreateFromStreetPartsInput(): array
    {
        return [
            ["Example\n42\nC\nhouse", ['Example', '42', 'C', 'house'], 'NL', 4],
            ["Example\n42\nA", ['Example 42A', '', ''], 'NL', 3],
            ["22 Example\nApartment 1", ['22 Example', 'Apartment 1', ''], 'US', 2],
            ["22 Example Apartment 1", ['22 Example', 'Apartment 1', ''], 'US', 1],
        ];
    }

    private function getScopeConfig(int $numberOfStreetlines = 2): ScopeConfigInterface
    {
        $mockup = $this->createMock(ScopeConfigInterface::class);
        $mockup->method('getValue')->willReturn($numberOfStreetlines);

        return $mockup;
    }
}
