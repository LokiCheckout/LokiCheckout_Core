<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\SetterInjection;

use Yireo\LokiCheckout\Config\Config;

trait InjectConfig
{
    protected ?Config $config = null;

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }
}
