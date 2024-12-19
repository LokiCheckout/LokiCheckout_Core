<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Directory\Model\Country\Postcode\ConfigInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Yireo\LokiCheckout\Component\Form\Field\AddressField;
use Yireo\LokiCheckout\Component\Form\Field\Field;

class PostcodeFormatValidator implements FieldValidatorInterface
{
    public function __construct(
        private ConfigInterface $postcodeConfig,
    ) {
    }

    public function validate(Field $component): bool
    {
        if (false === $component instanceof AddressField) {
            return true;
        }

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

        $component->getErrorContainer()->addError($this->getErrorMessageFromExamples($postcode, $examples));

        return false;
    }

    private function getErrorMessageFromExamples(string $postcode, array $examples): string
    {
        if (empty($examples)) {
            return (string)__('Postcode "%1" has invalid format', $postcode);
        }

        if (count($examples) === 1) {
            return (string)__('Postcode "%1" needs to be in the format %2', $postcode, $examples[0]);
        }

        return (string)__('Postcode "%1" needs to be in one of the following formats: %1', $postcode, implode(', ', $examples));
    }
}
