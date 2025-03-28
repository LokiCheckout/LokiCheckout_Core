<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Component\Checkout\Payment;

interface ShipmentMethodIconResolverInterface
{
    public function resolve(): false|string;
}
