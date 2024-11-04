<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Directory\Model\Country\Postcode\ConfigInterface;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

class PostcodeFormatValidator implements FieldValidatorInterface
{
    public function __construct(
        private ConfigInterface $postcodeConfig,
    ) {
    }

    public function validate(FieldComponent $component): bool
    {
        $countryId = $component->getAddressState()->load('country_id');
        $postcode = $component->getValue();

        if (empty($postcode)) {
            return true;
        }

        if (empty($countryId)) {
            return true;
        }

        $postcodes = $this->postcodeConfig->getPostCodes();
        if (empty($postcodes[$countryId]) || false === is_array($postcodes[$countryId])) {
            return true;
        }

        $patterns = $postcodes[$countryId];
        foreach ($patterns as $pattern) {
            preg_match('/'.(string)$pattern['pattern'].'/', $postcode, $matches);
            if (count($matches)) {
                return true;
            }
        }

        $component->error($component->getModelName(), 'Invalid postcode format');
        return false;
    }
}
