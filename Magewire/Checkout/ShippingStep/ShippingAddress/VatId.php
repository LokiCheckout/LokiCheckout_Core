<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Override;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Validator\Component\VatIdValidator;
use Magento\Customer\Model\Vat as VatModel;

class VatId extends ShippingAddressFormFieldComponent
{
    protected $listeners = ['shippingStepSave' => 'refresh'];

    public function __construct(
        private VatModel $vatModel,
        private VatIdValidator $vatIdValidator,
    ) {
    }

    public function getFieldTemplate(): string
    {
        if ($this->isEu()) {
            return 'Yireo_FriggaCheckout::generic/field/vat_id_eu.phtml';
        }

        return 'Yireo_FriggaCheckout::generic/field/text.phtml';
    }

    #[Override]
    public function boot(): void
    {
        parent::boot();
        $this->value = strtoupper($this->value);
    }

    #[Override]
    public function validate(): bool
    {
        if (parent::validate() === false) {
            return false;
        }

        $countryId = (string)$this->shippingAddressState->load('country_id');
        $errors = $this->vatIdValidator
            ->setVatId($this->getValue())
            ->setCountryId($countryId)
            ->validate();

        return $this->setErrorsFromValidation($errors);
    }

    public function isEu(): bool
    {
        // @todo: Billing or shipping?
        // @todo: Does the shipping address need VAT ID after all?
        $countryId = $this->getCountryIdFromQuote();
        return $this->vatModel->isCountryInEU($countryId);
    }

    public function getVatCountryId(): string
    {
        $countryId = $this->getCountryIdFromQuote();
        // @todo: Greek exception
        return $countryId;
    }

    private function getCountryIdFromQuote(): string
    {
        return (string)$this->shippingAddressState->load('country_id');
    }
}
