<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Trait;

trait AssertShippingMethodAvailable
{
    /**
     * @params $availableShippingMethods ShippingMethodInterface[]
     */
    protected function assertShippingMethodAvailable(string $expectedMethod, array $availableShippingMethods = []): void
    {
        $shippingMethodCodes = [];
        foreach ($availableShippingMethods as $availableShippingMethod) {
            $shippingMethodCodes[] = $availableShippingMethod->getCarrierCode()
                . '_'
                . $availableShippingMethod->getMethodCode();
        }

        $debugMsg = 'Available shipping rates: ';
        $debugMsg .= implode(', ', $shippingMethodCodes);

        $this->assertContains($expectedMethod, $shippingMethodCodes, $debugMsg);
    }
}
