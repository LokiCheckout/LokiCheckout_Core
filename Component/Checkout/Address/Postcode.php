<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address;

use Magento\Directory\Helper\Data as DirectoryData;
use Override;
use Yireo\LokiCheckout\Component\Form\Field\AddressField;

class Postcode extends AddressField
{
    protected array $validators = ['postcode'];
    protected array $filters = ['uppercase'];

    public function __construct(
        private DirectoryData $directoryData,
    ) {
    }

    #[Override]
    public function isRequired(): bool
    {
        $required = $this->getBlock()->getIsRequired();
        if (is_bool($required)) {
            return $required;
        }

        $countries = $this->directoryData->getCountriesWithOptionalZip();
        $countryId = $this->addressState->load('country_id');

        return false === in_array($countryId, $countries);
    }

    public function getFieldLabel(): string
    {
        if ($this->addressState->load('country_id') === 'US') {
            return (string)__('Zipcode');
        }

        return parent::getFieldLabel();
    }
}
