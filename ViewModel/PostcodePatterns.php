<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Directory\Model\Country\Postcode\ConfigInterface;

class PostcodePatterns implements ArgumentInterface
{
    public function __construct(
        private ConfigInterface $postcodeConfig,
    ) {
    }

    public function getConfiguration(): array
    {
        return $this->postcodeConfig->getPostCodes();
        foreach ($postcodePatterns as $countryCode => $patterns) {
            foreach ($patterns as $patternName => $patternData){
                $patterns[$patternName]['pattern'] = $this->fromPhpRegexToJsRegex($patternData['pattern']);
            }
        }

        return $postcodePatterns;
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
