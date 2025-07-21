<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Prefix;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldViewModel;

class PrefixViewModel extends AddressFieldViewModel
{
    public function getFieldLabel(): string
    {
        return 'Prefix';
    }

    public function isRequired(): bool
    {
        $showCompany = (string)$this->getContext()->getScopeConfig()->getValue('customer/address/prefix_show');

        if ($showCompany === 'req') {
            return true;
        }

        return parent::isRequired();
    }
}
