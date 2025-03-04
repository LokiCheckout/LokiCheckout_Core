<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Customer\Dob;

use Yireo\LokiCheckout\Component\Base\QuoteField\QuoteFieldViewModel;

class DobViewModel extends QuoteFieldViewModel
{
    public function getMax(): string
    {
        return date('Y-m-d', time());
    }

    public function getValue(): string
    {
        $value = parent::getValue();
        if (empty($value)) {
            return '';
        }

        return date('Y-m-d', strtotime((string)$value));
    }

    public function getFieldLabel(): string
    {
        return (string)__('Date of Birth');
    }

    public function getInputType(): string
    {
        return 'date';
    }

    public function isRequired(): bool
    {
        $showCompany = (string)$this->getContext()->getScopeConfig()->getValue('customer/address/dob_show');

        if ($showCompany === 'req') {
            return true;
        }

        return parent::isRequired();
    }
}
