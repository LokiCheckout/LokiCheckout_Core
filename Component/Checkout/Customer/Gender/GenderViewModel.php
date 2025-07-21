<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Customer\Gender;

use LokiCheckout\Core\Component\Base\QuoteField\QuoteFieldViewModel;

class GenderViewModel extends QuoteFieldViewModel
{
    public function getFieldLabel(): string
    {
        return (string)__('Gender');
    }

    public function isRequired(): bool
    {
        $showCompany = (string)$this->getContext()->getScopeConfig()->getValue('customer/address/gender_show');

        if ($showCompany === 'req') {
            return true;
        }

        return parent::isRequired();
    }
}
