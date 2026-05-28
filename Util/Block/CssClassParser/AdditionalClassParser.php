<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Block\CssClassParser;

use Loki\CssUtils\Util\CssClassParser\CssClassParserInterface;
use LokiCheckout\Core\Config\Config;
use Magento\Framework\View\Element\AbstractBlock;

class AdditionalClassParser implements CssClassParserInterface
{
    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function parse(array $cssClasses, string $scope, AbstractBlock $block): array
    {
        if ($scope !== 'block') {
            return $cssClasses;
        }

        if ($block->getNameInLayout() === 'loki-checkout') {
            $cssClasses[] = 'skin-'.$this->config->getSkin();
            $cssClasses[] = 'grid-layout-'.$this->config->getGridLayoutName();
        }

        return $cssClasses;
    }
}
