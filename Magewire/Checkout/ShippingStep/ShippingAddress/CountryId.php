<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class CountryId extends ShippingAddressFormFieldComponent implements FormSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'country_id';
    const FIELD_TYPE = 'select';

    use OptionsBehaviour;

    public function isRequired(): bool
    {
        return true;
    }
    public function updated($value, string $name)
    {
        $this->context->getCheckoutState()->getQuote()->getShippingAddress()->setData('region', '');
        $this->context->getCheckoutState()->getQuote()->getShippingAddress()->setData('region_id', '');
        return parent::updated($value, $name);
    }
}
