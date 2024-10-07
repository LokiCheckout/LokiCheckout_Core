<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\BillingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\FriggaCheckout\Source\Options\CountryOptions;
use Yireo\FriggaCheckout\Util\Context;

class CountryId extends BillingAddressFormFieldComponent implements FormSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'country_id';
    const FIELD_TYPE = 'select';

    public function __construct(
        Context $context,
        private CountryOptions $countryOptions,
    ) {
        parent::__construct($context);
    }
    public function updated($value, string $name)
    {
        $this->context->getCheckoutState()->getQuote()->getBillingAddress()->setData('region', '');
        $this->context->getCheckoutState()->getQuote()->getBillingAddress()->setData('region_id', '');
        return parent::updated($value, $name);
    }

    public function getOptions(): array
    {
        return $this->countryOptions->getOptions();
    }
}
