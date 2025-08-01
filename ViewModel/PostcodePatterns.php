<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Directory\Model\Country\Postcode\ConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class PostcodePatterns implements ArgumentInterface
{
    public function __construct(
        private readonly ConfigInterface $postcodeConfig,
    ) {
    }

    public function getConfiguration(): array
    {
        return $this->postcodeConfig->getPostCodes();
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
