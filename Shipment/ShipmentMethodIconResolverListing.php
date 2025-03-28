<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Payment;

class ShipmentMethodIconResolverListing
{
    public function __construct(
        private array $iconResolvers = []
    ) {
    }

    /**
     * @return PaymentMethodIconResolverInterface[]
     */
    public function getIconResolvers(): array
    {
        return $this->iconResolvers;
    }
}
