<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\Directory\Helper\Data as DirectoryData;
use Override;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\LokiCheckout\Validator\Component\PostcodeValidator;

class Postcode extends ShippingAddressFormFieldComponent
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    public function __construct(
        private DirectoryData $directoryData,
        private PostcodeValidator $postcodeValidator,
    ) {
    }

    #[Override]
    public function isRequired(): bool
    {
        $countries = $this->directoryData->getCountriesWithOptionalZip();
        $countryId = $this->shippingAddressState->load('country_id');

        return false === in_array($countryId, $countries);
    }

    #[Override]
    public function validate(): bool
    {
        if (parent::validate() === false) {
            return false;
        }

        $countryId = $this->shippingAddressState->load('country_id');
        $errors = $this->postcodeValidator
            ->setCountryId($countryId)
            ->setPostcode($this->getValue())
            ->validate();

        return $this->setErrorsFromValidation($errors);
    }
}
