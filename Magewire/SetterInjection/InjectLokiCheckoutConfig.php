<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\SetterInjection;

use Yireo\LokiCheckout\Config\Config as LokiCheckoutConfig;

trait InjectLokiCheckoutConfig
{
    protected ?LokiCheckoutConfig $lokiCheckoutConfig = null;

    public function setLokiCheckoutConfig(LokiCheckoutConfig $lokiCheckoutConfig)
    {
        $this->lokiCheckoutConfig = $lokiCheckoutConfig;
    }
}
