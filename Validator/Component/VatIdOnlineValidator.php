<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Model\Vat as VatModel;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;
use Yireo\LokiCheckout\Util\CountryIdVatPrefixMapping;

class VatIdOnlineValidator implements AddressFieldValidatorInterface
{
    public function __construct(
        private CountryIdVatPrefixMapping $countryIdVatPrefixMapping,
        private VatModel $vatModel
    ) {
    }
    public function validate(AddressFieldComponent $component): bool
    {
        $countryId = $component->getAddressState()->load('country_id');
        $vatId = $component->getValue();

        if (empty($vatId) || empty($countryId)) {
            return true;
        }

        if (false === $this->vatModel->isCountryInEU($countryId)) {
            return true;
        }

        $vatCountryId = $this->countryIdVatPrefixMapping->convert($countryId);
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
