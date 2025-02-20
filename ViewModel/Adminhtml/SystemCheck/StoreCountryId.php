<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

class StoreCountryId extends ScopeConfigValue
{
    protected function getScopeConfigPath(): string
    {
        return 'general/store_information/country_id';
    }


    protected function isValid($value): bool
    {
        return !empty($value);
    }
    protected function getRecommendation(): string
    {
        return 'Make sure to enter a valid country for this store';
    }
}
