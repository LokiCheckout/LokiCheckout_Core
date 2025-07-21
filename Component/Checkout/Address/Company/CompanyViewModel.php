<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Company;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldViewModel;

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
