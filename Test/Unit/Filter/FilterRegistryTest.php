<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Filter;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Filter\FilterRegistry;

class FilterRegistryTest extends TestCase
{
    public function testGetSelectedFilters()
    {
        $filterRegistry = new FilterRegistry(['item1' => 'filter1', 'item2' => 'filter2']);
        $this->assertCount(2, $filterRegistry->getSelectedFilters(['item1', 'item2', 'item3']));
    }
}
