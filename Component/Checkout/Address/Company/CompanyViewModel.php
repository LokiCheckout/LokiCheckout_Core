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

    public function getHelpText(): string
    {
        if ($this->getContext()->getModuleConfig()->enableVatIdOnlyForCompany()) {
            return 'To enter a VAT ID, first enter a company name';
        }

        return parent::getHelpText();
    }
}
