<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\VatId;

use Override;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

class VatIdViewModel extends AddressFieldViewModel
{
    #[Override]
    public function isAllowRendering(): bool
    {
        if (false === $this->getContext()->getModuleConfig()->enableVatIdOnlyForCompany()) {
            return parent::isAllowRendering();
        }

        $company = $this->getAddressState()->load('company');
        if (!empty($company)) {
            return parent::isAllowRendering();
        }

        return false;
    }

    public function isRequired(): bool
    {
        $showCompany = (string)$this->getContext()->getScopeConfig()->getValue('customer/address/taxvat_show');

        if ($showCompany === 'req') {
            return true;
        }

        return parent::isRequired();
    }
}
