<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Trait;

use Magento\Quote\Model\Quote\Address;

trait AssertAddressHasShippingMethod
{
    protected function assertAddressHasShippingMethod(string $expectedMethod, Address $address): void
    {
        $this->assertSame($expectedMethod, $address->getShippingMethod());
    }
}
