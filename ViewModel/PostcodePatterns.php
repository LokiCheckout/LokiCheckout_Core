<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Directory\Model\AllowedCountries;
use Magento\Directory\Model\Country\Postcode\ConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class PostcodePatterns implements ArgumentInterface
{
    public function __construct(
        private readonly ConfigInterface $postcodeConfig,
        private readonly AllowedCountries $allowedCountries
    ) {
    }

    public function getConfiguration(): array
    {
        $postcodes =  $this->postcodeConfig->getPostCodes();
        foreach ($postcodes as $countryId => $postcode) {
            if (false === $this->isCountryEnabled($countryId)) {
                unset($postcodes[$countryId]);
            }
        }

        return $postcodes;
    }

    private function isCountryEnabled(string $countryId): bool
    {
        static $allowedCountries = null;
        if (null === $allowedCountries){
            $allowedCountries = $this->allowedCountries->getAllowedCountries();
        }

        if (false === in_array($countryId, $allowedCountries)) {
            return false;
        }

        return true;
    }

    private function fromPhpRegexToJsRegex(string $regex): string
    {
        if (str_starts_with($regex, '^')) {
            //$regex = ltrim($regex, '^');
        }

        if (str_ends_with($regex, '$')) {
            //$regex = rtrim($regex, '$');
        }

        $regex = '/' . $regex . '/';

        return $regex;
    }
}
