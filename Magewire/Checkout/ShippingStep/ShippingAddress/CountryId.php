<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\MagewireCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\MagewireCheckout\Magewire\Generic\FormSelectFieldInterface;
use Yireo\MagewireCheckout\Source\Options\CountryOptions;
use Yireo\MagewireCheckout\Util\Context;

class CountryId extends ShippingAddressFormFieldComponent implements FormSelectFieldInterface
{
    const ATTRIBUTE_CODE = 'country_id';
    const FIELD_TYPE = 'select';

    public function __construct(
        Context $context,
        private CountryOptions $countryOptions,
    ) {
        parent::__construct($context);
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
