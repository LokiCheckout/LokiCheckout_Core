<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Override;
use Magento\Directory\Helper\Data as DirectoryData;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;

class Postcode extends ShippingAddressFieldComponent
{
    protected $listeners = ['shippingStepSave' => 'refresh'];
    protected array $validators = ['postcode'];
    protected array $filters = ['uppercase'];

    public function __construct(
        private DirectoryData $directoryData,
    ) {
    }

    #[Override]
    public function isRequired(): bool
    {
        $countries = $this->directoryData->getCountriesWithOptionalZip();
        $countryId = $this->addressState->load('country_id');

        return false === in_array($countryId, $countries);
    }
}
