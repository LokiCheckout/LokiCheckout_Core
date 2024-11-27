<?php

namespace Yireo\LokiCheckout\Test\Unit\Util;

use PHPUnit\Framework\TestCase;
use Yireo\LokiCheckout\Util\Streetlines;

class StreetlinesTest extends TestCase
{
    public function testInstantiation()
    {
        $streetlines = new Streetlines('Example Street', '1', 'A');
        $this->assertSame('Example Street', $streetlines->getStreetName());
        $this->assertSame('1', $streetlines->getHouseNumber());
        $this->assertSame('A', $streetlines->getHouseNumberAddition());
    }
}
