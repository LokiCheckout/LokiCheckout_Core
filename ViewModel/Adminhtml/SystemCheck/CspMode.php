<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\Adminhtml\SystemCheck;

class CspMode extends ScopeConfigValue
{
    protected function getScopeConfigPath(): string
    {
        return 'csp/mode/storefront_checkout_index_index/report_only';
    }

    protected function getRecommendation(): string
    {
        return 'CSP Strict Mode can only be enabled if your theme and other extensions allow for it';
    }
}
