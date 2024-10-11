<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Rakit\Validation\Validator;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\FriggaCheckout\Source\Options\CountryOptions;
use Yireo\FriggaCheckout\Util\Context;

class CountryId extends ShippingAddressFormFieldComponent implements FormSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'country_id';
    const FIELD_TYPE = 'select';

    public function __construct(
        Validator $validator,
        Context $context,
        private CountryOptions $countryOptions,
    ) {
        parent::__construct($validator, $context);
    }

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

    public function getOptions(): array
    {
        return $this->countryOptions->getOptions();
    }
}
