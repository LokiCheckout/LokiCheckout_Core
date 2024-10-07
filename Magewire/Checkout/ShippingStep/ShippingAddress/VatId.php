<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\MagewireCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\MagewireCheckout\Util\Context;
use Yireo\MagewireCheckout\Validator\VatIdValidator;

class VatId extends ShippingAddressFormFieldComponent
{
    protected $listeners = ['quoteSave' => 'refresh'];

    const ATTRIBUTE_CODE = 'vat_id';

    public function __construct(
        private VatIdValidator $vatIdValidator,
        Context $context)  {
        parent::__construct($context);
    }

    public function getFieldType(): string
    {
        return 'vat_id';
    }

    public function boot(): void
    {
        $this->value = (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getVatId();
    }

    public function isValid(): bool
    {
        return true;
        if (parent::isValid() === false) {
            return false;
        }

        $countryId = (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getCountryId();
        $errors = $this->vatIdValidator
            ->setVatId($this->getValue())
            ->setCountryId($countryId)
            ->validate();

        return $this->setErrorsFromValidation($errors);
    }
}
