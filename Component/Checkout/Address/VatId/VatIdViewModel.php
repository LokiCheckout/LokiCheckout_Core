<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\VatId;

use Override;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

class VatIdViewModel extends AddressFieldViewModel
{
    #[Override]
    public function isAllowRendering(): bool
    {
        if (false === $this->getContext()->moduleConfig->enableVatIdOnlyForCompany()) {
            return parent::isAllowRendering();
        }

        $company = $this->getAddressState()->load('company');
        if (!empty($company)) {
            parent::isAllowRendering();
        }

        return false;
    }
}
