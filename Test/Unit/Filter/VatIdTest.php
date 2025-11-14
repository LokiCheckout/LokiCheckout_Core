<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Filter;

use Loki\Components\Filter\FilterScope;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Filter\VatId;

class VatIdTest extends TestCase
{
    /**
     * @param string $vatId
     * @return void
     * @dataProvider getValues
     */
    public function testFilter(string $vatId, string $expectedValue)
    {
        $filter = new VatId();
        $filterScope = new FilterScope();
        $this->assertEquals($expectedValue, $filter->filter($vatId, $filterScope));
    }

    public function getValues(): array
    {
        return [
            ['NL12341234', 'NL12341234'],
            ['NL 12341234', 'NL12341234'],
            ['nl 1234-12-34', 'NL12341234'],
        ];
    }
}
