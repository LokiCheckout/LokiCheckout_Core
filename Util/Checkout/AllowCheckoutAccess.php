<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use LokiCheckout\Core\Config\Config;

class AllowCheckoutAccess
{
    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function allow(): bool
    {
        return $this->config->isEnabled();
    }
}
