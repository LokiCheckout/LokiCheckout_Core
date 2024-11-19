<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Module\Manager as ModuleManager;

class Config
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private ModuleManager $moduleManager,
    ) {
    }

    public function isLokiCheckout(): bool
    {
        // @todo: Find a better way to do this
        // @todo: Implement this method everywhere
        return true;
    }

    public function isEnabled(): bool
    {
        if (false === $this->moduleManager->isEnabled('Magewirephp_Magewire')) {
            return false;
        }

        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/general/enable');
    }

    public function enableSameAsBilling(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/general/enable_same_as_billing');
    }

    public function enableVatIdOnlyForCompany(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/general/enable_vat_id_only_for_company');
    }

    public function isDebug(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/general/debug');
    }
}
