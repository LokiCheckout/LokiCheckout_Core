<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Trait;

use Magento\Quote\Api\Data\AddressInterface;

trait AssertAddressHasShippingMethod
{
    protected function assertAddressHasShippingMethod(string $expectedMethod, AddressInterface $address): void
    {
        $this->assertSame($expectedMethod, $address->getShippingMethod());
    }
}
