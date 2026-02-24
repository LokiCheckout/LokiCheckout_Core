<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Filter;

use Loki\Components\Filter\FilterScope;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Filter\CountryId;

class CountryIdTest extends TestCase
{
    /**
     * @param string $countryId
     * @return void
     * @dataProvider getValues
     */
    public function testFilter(string $countryId, string $expectedValue)
    {
        $filter = new CountryId();
        $filterScope = new FilterScope();
        $this->assertEquals($expectedValue, $filter->filter($countryId, $filterScope));
    }

    public static function getValues(): array
    {
        return [
            ['NL', 'NL'],
            ['nl', 'NL'],
            ['USD', 'US'],
            [' NL ', 'NL'],
            [' f1o2o3bar ', 'FO'],
        ];
    }
}
