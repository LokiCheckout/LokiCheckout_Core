<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Model\Vat as VatModel;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Util\CountryIdVatPrefixMapping;

class VatIdValidator implements AddressFieldValidatorInterface
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
        if (!preg_match('/^'.$vatCountryId.'/', $vatId)) {
            $component->error('value', 'Your VAT ID does not start with the right country code');
            return false;
        }

        if (strlen($vatId) < 10) {
            $component->error('value', 'Your VAT ID is too short');
            return false;
        }

        return true;
    }
}
