<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Config implements ArgumentInterface
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private ModuleManager $moduleManager,
        private XmlConfig $xmlConfig
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
        if (false === $this->moduleManager->isEnabled('Yireo_LokiComponents')) {
            return false;
        }

        if (false === $this->moduleManager->isEnabled('Yireo_LokiCheckout')) {
            return false;
        }

        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/general/enable');
    }

    public function getTheme()
    {
        $theme = (string)$this->scopeConfig->getValue('yireo_loki_checkout/general/theme');
        if (false === array_key_exists($theme, $this->xmlConfig->getThemes())) {
            return 'default';
        }

        return $theme;
    }

    public function isDebug(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/debug/enable');
    }

    public function addDebugBlock(): bool
    {
        return $this->isDebug() && (bool)$this->scopeConfig->getValue('yireo_loki_checkout/debug/debug_block');
    }

    public function addHtmlHints(): bool
    {
        return $this->isDebug() && (bool)$this->scopeConfig->getValue('yireo_loki_checkout/debug/html_hints');
    }

    public function useRequiredBlock(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/fields/use_required_block');
    }

    public function enableSameAsBilling(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/fields/enable_same_as_billing');
    }

    public function enableVatIdOnlyForCompany(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/fields/enable_vat_id_only_for_company');
    }

    public function hideRegionWhenOptional(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/fields/hide_region_when_optional');
    }

    public function selectDefaultShippingMethod(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/shipping/select_default_shipping_method');
    }

    public function selectDefaultPaymentMethod(): bool
    {
        return (bool)$this->scopeConfig->getValue('yireo_loki_checkout/billing/select_default_payment_method');
    }
}
