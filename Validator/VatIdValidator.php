<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Validator;

class VatIdValidator implements ValidatorInterface
{
    private string $vatId;
    private string $countryId;

    public function __construct(
    ) {
    }

    public function setVatId(string $vatId): VatIdValidator
    {
        $this->vatId = $vatId;
        return $this;
    }

    public function setCountryId(string $countryId): VatIdValidator
    {
        $this->countryId = $countryId;
        return $this;
    }

    public function validate(): true|array
    {
        if (empty($this->vatId)) {
            return true;
        }

        if (empty($this->countryId)) {
            return true;
        }

        return true;
        // @todo: Check for format CC123456789
        // @todo: Check if VAT ID is valid

        return ['Something is still wrong'];
    }
}
