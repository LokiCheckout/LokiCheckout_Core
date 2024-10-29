<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Override;
use Magento\Directory\Helper\Data as DirectoryData;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

class Postcode extends AddressFieldComponent
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
        $countries = $this->directoryData->getCountriesWithOptionalZip();
        $countryId = $this->addressState->load('country_id');

        return false === in_array($countryId, $countries);
    }
}
