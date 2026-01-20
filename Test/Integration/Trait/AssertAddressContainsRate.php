<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Trait;

use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Rate;

trait AssertAddressContainsRate
{
    protected function assertAddressContainsRate(string $rateMethod, Address $address): void
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
