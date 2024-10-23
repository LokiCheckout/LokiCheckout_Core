<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Filter;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Filter\VatId;

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
        $this->assertEquals($expectedValue, $filter->filter($vatId));
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
