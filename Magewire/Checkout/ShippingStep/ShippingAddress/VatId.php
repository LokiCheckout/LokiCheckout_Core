<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;
use Magento\Customer\Model\Vat as VatModel;
use Yireo\LokiCheckout\Util\CountryIdVatPrefixMapping;

class VatId extends ShippingAddressFieldComponent
{
    protected $listeners = ['shippingStepSave' => 'refresh'];
    protected array $validators = ['vatId'];
    protected array $filters = ['uppercase'];

    public function __construct(
        private CountryIdVatPrefixMapping $countryIdVatPrefixMapping,
        private VatModel $vatModel,
    ) {
    }

    public function getFieldTemplate(): string
    {
        if ($this->isEu()) {
            return 'Yireo_LokiCheckout::form/field/vat_id_eu.phtml';
        }

        return 'Yireo_LokiCheckout::form/field/text.phtml';
    }

    public function isEu(): bool
    {
        // @todo: Does the shipping address need VAT ID after all?
        return $this->vatModel->isCountryInEU($this->getCountryIdFromQuote());
    }

    public function getVatCountryId(): string
    {
        return $this->countryIdVatPrefixMapping->convert($this->getCountryIdFromQuote());
    }

    private function getCountryIdFromQuote(): string
    {
        return (string)$this->addressState->load('country_id');
    }
}
