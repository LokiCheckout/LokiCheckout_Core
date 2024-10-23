<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Filter;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Filter\CountryId;

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
        $this->assertEquals($expectedValue, $filter->filter($countryId));
    }

    public function getValues(): array
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
