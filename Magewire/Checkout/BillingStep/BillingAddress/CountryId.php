<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;

class CountryId extends BillingAddressFormFieldComponent implements FormSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'country_id';
    const FIELD_TYPE = 'select';

    use OptionsBehaviour;

    public function updated($value, string $name)
    {
        $this->context->getCheckoutState()->getQuote()->getBillingAddress()->setData('region', '');
        $this->context->getCheckoutState()->getQuote()->getBillingAddress()->setData('region_id', '');

        return parent::updated($value, $name);
    }
}
