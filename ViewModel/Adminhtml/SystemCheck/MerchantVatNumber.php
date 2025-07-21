<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\Adminhtml\SystemCheck;

class MerchantVatNumber extends ScopeConfigValue
{
    protected function getScopeConfigPath(): string
    {
        return 'general/store_information/merchant_vat_number';
    }

    protected function isValid($value): bool
    {
        return !empty($value);
    }
    protected function getRecommendation(): string
    {
        return 'Make sure to enter a valid VAT number';
    }
}
