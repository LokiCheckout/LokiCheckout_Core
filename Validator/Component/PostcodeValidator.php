<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Validator\Component;

use Magento\Directory\Model\Country\Postcode\ConfigInterface;
use Yireo\FriggaCheckout\Validator\ValidatorInterface;

class PostcodeValidator implements ValidatorInterface
{
    private string $postcode;
    private string $countryId;

    public function __construct(
        private ConfigInterface $postcodeConfig
    ) {
    }

    public function setPostcode(string $postcode): PostcodeValidator
    {
        $this->postcode = $postcode;
        return $this;
    }

    public function setCountryId(string $countryId): PostcodeValidator
    {
        $this->countryId = $countryId;
        return $this;
    }

    public function validate(): true|array
    {
        if (empty($this->postcode)) {
            return true;
        }

        if (empty($this->countryId)) {
            return true;
        }

        $postcodes = $this->postcodeConfig->getPostCodes();
        if (empty($postcodes[$this->countryId]) || false === is_array($postcodes[$this->countryId])) {
            return true;
        }

        $patterns = $postcodes[$this->countryId];
        foreach ($patterns as $pattern) {
            preg_match('/'.(string)$pattern['pattern'].'/', $this->postcode, $matches);
            if (count($matches)) {
                return true;
            }
        }

        return ['Invalid postcode format'];
    }
}
