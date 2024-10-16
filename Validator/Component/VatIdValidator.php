<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Model\Vat as VatModel;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

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

        $vatId = $this->vatId;
        $vatCountryId = $this->getCountryIdToVatCountryId($this->countryId);
        if (!preg_match('/^'.$this->countryId.'/', $this->countryId)) {
            return ['Your VAT ID does not start with the right country code'];
        }

        if (strlen($vatId) < 10) {
            return ['Your VAT ID is too short'];
        }

        return $this->validateOnline($vatCountryId, $vatId);
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

    private function validateOnline(string $vatCountryId, string $vatId): true|array
    {
        $merchantCountryCode = $this->vatModel->getMerchantCountryCode();
        $merchantVatNumber = $this->vatModel->getMerchantVatNumber();
        if (empty($merchantVatNumber) || empty($merchantCountryCode)) {
            // @todo: Add a setting to throw a warning if this is not configured (or just ignore)
            return true;
        }

        $result = $this->vatModel->checkVatNumber(
            $vatCountryId,
            $vatId,
            $merchantCountryCode,
            $merchantVatNumber
        );

        if ($result->getIsValid() && $result->getRequestSuccess()) {
            return true;
        }

        return [(string)$result->getRequestMessage()];
    }
}
