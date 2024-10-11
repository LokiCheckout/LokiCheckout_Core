<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Directory\Helper\Data as DirectoryData;
use Rakit\Validation\Validator;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Util\Context;
use Yireo\FriggaCheckout\Validator\PostcodeValidator;

class Postcode extends ShippingAddressFormFieldComponent
{
    const ATTRIBUTE_CODE = 'postcode';

    protected $listeners = ['quoteSave' => 'refresh'];

    public function __construct(
        Validator $validator,
        Context $context,
        private DirectoryData $directoryData,
        private PostcodeValidator $postcodeValidator,
    ) {
        parent::__construct($validator, $context);
    }

    public function isRequired(): bool
    {
        $countries = $this->directoryData->getCountriesWithOptionalZip();
        $countryId = $this->context->getCheckoutState()->getQuote()->getShippingAddress()->getCountryId();

        return false === in_array($countryId, $countries);
    }

    public function isValid(): bool
    {
        if (parent::isValid() === false) {
            return false;
        }

        $countryId = (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getCountryId();
        $errors = $this->postcodeValidator
            ->setCountryId($countryId)
            ->setPostcode($this->getValue())
            ->validate();

        return $this->setErrorsFromValidation($errors);
    }
}
