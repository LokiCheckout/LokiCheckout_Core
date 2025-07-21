<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use Magento\Directory\Model\Country\Postcode\ConfigInterface;
use LokiCheckout\Core\Util\Component\AddressStateProvider;
use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;

class PostcodeFormatValidator implements ValidatorInterface
{
    public function __construct(
        private readonly ConfigInterface $postcodeConfig,
        private readonly AddressStateProvider $addressStateProvider
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        $addressState = $this->addressStateProvider->getAddressState($component->getBlock());
        $countryId = $addressState->load('country_id');
        $postcode = (string)$value;

        if ($postcode === '' || $postcode === '0') {
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
            preg_match('/' . (string)$pattern['pattern'] . '/', $postcode, $matches);
            if ($matches !== []) {
                return true;
            }
        }

        return [$this->getErrorMessageFromExamples($postcode, $examples)];
    }

    private function getErrorMessageFromExamples(string $postcode, array $examples): string
    {
        if ($examples === []) {
            return (string)__('Postcode "%1" has invalid format', $postcode);
        }

        if (count($examples) === 1) {
            return (string)__('Postcode "%1" needs to be in the format "%2"', $postcode, $examples[0]);
        }

        return (string)__('Postcode "%1" needs to be in one of the following formats: %2', $postcode, implode(', ', $examples));
    }
}
