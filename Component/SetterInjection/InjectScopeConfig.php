<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\SetterInjection;

use Magento\Framework\App\Config\ScopeConfigInterface;

trait InjectScopeConfig
{
    protected ?ScopeConfigInterface $scopeConfig = null;

    public function setScopeConfig(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }
}
