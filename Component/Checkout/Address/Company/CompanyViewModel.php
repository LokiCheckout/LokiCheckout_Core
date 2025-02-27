<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Company;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

class CompanyViewModel extends AddressFieldViewModel
{
    public function isRequired(): bool
    {
        $showCompany = (string)$this->getContext()->getScopeConfig()->getValue('customer/address/company_show');

        if ($showCompany === 'req') {
            return true;
        }

        return parent::isRequired();
    }
}
