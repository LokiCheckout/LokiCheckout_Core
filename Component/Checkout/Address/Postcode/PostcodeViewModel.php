<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Postcode;

use Override;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

/**
 * @method PostcodeContext getContext()
 */
class PostcodeViewModel extends AddressFieldViewModel
{
    #[Override]
    public function isRequired(): bool
    {
        $required = $this->getBlock()->getIsRequired();
        if (is_bool($required)) {
            return $required;
        }

        $countries = $this->getContext()->directoryData->getCountriesWithOptionalZip();
        $countryId = $this->getAddressState()->load('country_id');

        return false === in_array($countryId, $countries);
    }

    #[Override]
    public function getFieldLabel(): string
    {
        if ($this->getAddressState()->load('country_id') === 'US') {
            return (string)__('Zipcode');
        }

        return parent::getFieldLabel();
    }
}
