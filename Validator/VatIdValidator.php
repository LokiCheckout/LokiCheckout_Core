<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Validator;

use Magento\Customer\Model\Vat as VatModel;

class VatIdValidator implements ValidatorInterface
{
    private string $vatId;
    private string $countryId;

    public function __construct(
        private VatModel $vatModel
    ) {
    }

    public function setVatId(string $vatId): VatIdValidator
    {
        // @todo: Test whether this could be hacked?
        $this->vatId = preg_replace('/[^A-Z0-9]+/', '', strtoupper($vatId));
        return $this;
    }

    public function setCountryId(string $countryId): VatIdValidator
    {
        // @todo: Test whether this could be hacked?
        $this->countryId = preg_replace('/[^A-Z0-9]+/', '', strtoupper($countryId));
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

        if (false === $this->vatModel->isCountryInEU($this->countryId)) {
            return true;
        }

        $vatCountryId = $this->getCountryIdToVatCountryId($this->countryId);
        if (!preg_match('/^'.$this->countryId.'/', $this->countryId)) {
            return ['Your VAT ID does not start with the right country code'];
        }

        if (strlen($this->vatId) < 10) {
            return ['Your VAT ID is too short'];
        }

        // @todo: Check if VAT ID is valid
        return true;
    }

    private function getCountryIdToVatCountryId(string $countryId): string
    {
        $mapping = [
            'AT' => 'ATU',
            'GR' => 'EL',
        ];

        if (array_key_exists($countryId, $mapping)) {
            return $mapping[$countryId];
        }

        return $countryId;
    }
}
