<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Util\Context;
use Yireo\FriggaCheckout\Validator\VatIdValidator;

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
        $this->value = strtoupper((string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getVatId());
        $this->isValid();
    }

    public function isValid(): bool
    {
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
