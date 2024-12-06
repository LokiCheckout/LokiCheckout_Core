<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Magento\Directory\Helper\Data as DirectoryData;
use Override;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

abstract class Postcode extends AddressFieldComponent
{
    protected array $validators = ['postcode'];
    protected array $filters = ['uppercase'];

    public function __construct(
        private DirectoryData $directoryData,
    ) {
    }

    public function boot(): void
    {
        parent::boot();
        $this->addFieldRefreshListener('country_id');
    }

    #[Override]
    public function isRequired(): bool
    {
        $required = $this->getParent()->getData('is_required');
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
