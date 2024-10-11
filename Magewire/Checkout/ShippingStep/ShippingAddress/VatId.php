<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Rakit\Validation\Validator;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;
use Yireo\FriggaCheckout\Util\Context;
use Yireo\FriggaCheckout\Validator\VatIdValidator;

class VatId extends ShippingAddressFormFieldComponent
{
    protected $listeners = ['quoteSave' => 'refresh'];

    const ATTRIBUTE_CODE = 'vat_id';

    public function __construct(
        Validator $validator,
        Context $context,
        private VatIdValidator $vatIdValidator,
    ) {
        parent::__construct($validator, $context);
    }

    public function getFieldType(): string
    {
        return 'vat_id';
    }

    public function boot(): void
    {
        $this->value = strtoupper(
            (string)$this->context->getCheckoutState()->getQuote()->getShippingAddress()->getVatId()
        );
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
