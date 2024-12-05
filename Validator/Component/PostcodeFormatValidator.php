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

        $examples = [];
        foreach ($patterns as $pattern) {
            $examples[] = $pattern['example'];
            preg_match('/'.(string)$pattern['pattern'].'/', $postcode, $matches);
            if (count($matches)) {
                return true;
            }
        }

        $component->error('value', $this->getErrorMessageFromExamples($examples));
        return false;
    }

    private function getErrorMessageFromExamples(array $examples): string
    {
        if (empty($examples)) {
            return (string)__('Invalid postcode format');
        }

        if (count($examples) === 1) {
            return (string)__('Postcode needs to be in the format %1', $examples[0]);
        }

        return (string)__('Postcode needs to be in one of the following formats: %1', implode(', ', $examples));
    }
}
