<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Model\Vat as VatModel;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;
use Yireo\LokiCheckout\Util\CountryIdVatPrefixMapping;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

class VatIdValidator implements AddressFieldValidatorInterface
{
    public function __construct(
        private AddressStateInterface $addressState,
        private CountryIdVatPrefixMapping $countryIdVatPrefixMapping,
        private VatModel $vatModel
    ) {
    }
    public function validate(AddressFieldComponent $component): bool
    {
        $countryId = $this->addressState->load('country_id');
        // @todo: Test whether this could be hacked?
        // @todo: Move this to a filter
        $countryId = preg_replace('/[^A-Z0-9]+/', '', strtoupper($countryId));

        $vatId = $component->getValue();
        // @todo: Test whether this could be hacked?
        // @todo: Move this to a filter
        $vatId = preg_replace('/[^A-Z0-9]+/', '', strtoupper($vatId));

        if (empty($vatId) || empty($countryId)) {
            return true;
        }

        if (false === $this->vatModel->isCountryInEU($countryId)) {
            return true;
        }

        $vatCountryId = $this->countryIdVatPrefixMapping->convert($countryId);
        if (!preg_match('/^'.$countryId.'/', $countryId)) {
            $component->error('value', 'Your VAT ID does not start with the right country code');
            return false;
        }

        if (strlen($vatId) < 10) {
            $component->error('value', 'Your VAT ID is too short');
            return false;
        }

        return $this->validateOnline($component, $vatCountryId, $vatId);
    }

    private function validateOnline(AddressFieldComponent $component, string $vatCountryId, string $vatId): bool
    {
        $merchantCountryCode = $this->vatModel->getMerchantCountryCode();
        $merchantVatNumber = $this->vatModel->getMerchantVatNumber();
        if (empty($merchantVatNumber) || empty($merchantCountryCode)) {
            // @todo: Add a setting to throw a warning if this is not configured (or just ignore)
            return true;
        }

        $result = $this->vatModel->checkVatNumber(
            $vatCountryId,
            $vatId,
            $merchantCountryCode,
            $merchantVatNumber
        );

        if ($result->getIsValid() && $result->getRequestSuccess()) {
            return true;
        }


        $component->error('value', (string)$result->getRequestMessage());
        return false;
    }
}
