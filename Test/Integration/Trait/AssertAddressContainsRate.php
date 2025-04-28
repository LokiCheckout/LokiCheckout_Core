<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Trait;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Address\Rate;

trait AssertAddressContainsRate
{
    protected function assertAddressContainsRate(string $rateMethod, AddressInterface $address): void
    {
        $found = false;
        $foundRateMethods = [];
        foreach ($address->getShippingRatesCollection() as $shippingRate) {
            /** @var Rate $shippingRate */
            $foundRateMethods[] = $shippingRate->getMethod();
            if ((string)$shippingRate->getMethod() === $rateMethod) {
                $found = true;
                break;
            }
        }

        $debugMsg = 'Rate "' . $rateMethod . '" not found: ' . implode(', ', $foundRateMethods);
        $this->assertTrue($found, $debugMsg);
    }
}
