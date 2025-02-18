<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel\Adminhtml\SystemCheck;

class AddressLines extends ScopeConfigValue
{
    protected function getScopeConfigPath(): string
    {
        return 'customer/address/street_lines';
    }

    protected function getRecommendation(): string
    {
        return 'Most of the time, 3 or 4 is a good idea';
    }
}
