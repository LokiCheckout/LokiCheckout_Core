<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block\CssClassParser;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiCheckout\Exception\BlockNotFoundException;
use Yireo\LokiComponents\Util\Block\CssClassParser\CssClassParserInterface;

class GridClassParser implements CssClassParserInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly XmlConfig $xmlConfig,
    ) {
    }

    public function parse(array $cssClasses, string $scope, AbstractBlock $block): array
    {
        if ($scope !== 'block') {
            return $cssClasses;
        }

        $gridClassesFromXmlConfig = $this->getGridClassesFromXmlConfig($block);
        if ($gridClassesFromXmlConfig === '' || $gridClassesFromXmlConfig === '0') {
            $gridClassesFromXmlConfig = $this->getDefaultGridClasses($block);
        }

        $cssClasses = array_merge_recursive($cssClasses, [
            'grid' => $gridClassesFromXmlConfig,
        ]);

        return $cssClasses;
    }

    private function getGridClassesFromXmlConfig(AbstractBlock $block): string
    {
        $gridLayoutName = $this->config->getCurrentGridLayoutName();
        $blockAlias = $block->getLayout()->getElementAlias($block->getNameInLayout());
        if (empty($blockAlias)) {
            $blockParts = explode('.', $block->getNameInLayout());
            $blockAlias = array_pop($blockParts);
        }

        if (empty($blockAlias)) {
            return '';
        }

        $gridLayout = $this->xmlConfig->getGridLayout($gridLayoutName);

        try {
            $block = $gridLayout->getBlock($blockAlias);
        } catch (BlockNotFoundException $exception) {
            return '';
        }

        return $block->getGridClass();
    }

    private function getDefaultGridClasses(AbstractBlock $block): string
    {
        $parentBlock = $block->getParentBlock();
        if (false === $parentBlock instanceof AbstractBlock) {
            return '';
        }

        if (false === (bool)$parentBlock->getGrid()) {
            return '';
        }

        return $this->config->getDefaultGridClasses();
    }
}
