<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Directory\Helper\Data as DirectoryData;
use Yireo\MagewireCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\MagewireCheckout\Util\Context;

class Postcode extends ShippingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'postcode';

    protected $listeners = ['quoteSave' => 'refresh'];

    public function __construct(
        private DirectoryData $directoryData,
        Context $context) {
        parent::__construct($context);
    }

    public function isRequired(): bool
    {
        $countries = $this->directoryData->getCountriesWithOptionalZip();
        $countryId = $this->context->getCheckoutState()->getQuote()->getShippingAddress()->getCountryId();
        return false === in_array($countryId, $countries);
    }
}
