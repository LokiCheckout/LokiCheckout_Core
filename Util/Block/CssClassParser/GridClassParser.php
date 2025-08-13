<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Block\CssClassParser;

use Magento\Framework\View\Element\AbstractBlock;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Exception\BlockNotFoundException;
use Loki\Components\Util\Block\CssClassParser\CssClassParserInterface;

class GridClassParser implements CssClassParserInterface
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

        $gridClassesFromXmlConfig = $this->getGridClassesFromXmlConfig($block);
        if ($gridClassesFromXmlConfig === '' || $gridClassesFromXmlConfig === '0') {
            $gridClassesFromXmlConfig = $this->getDefaultGridClasses($block);
        }

        $sortOrder = $block->getSortOrder();
        $sortOrderClass = '';
        if ($sortOrder) {
            //$sortOrderClass = ' order-[--checkout-field-order-' . $sortOrder.']';
            $sortOrderClass = ' order-[' . $sortOrder.']'; // @todo: sortOrder
        }

        $cssClasses = array_merge_recursive($cssClasses, [
            'grid' => $gridClassesFromXmlConfig,
            'sortOrder' => $sortOrderClass,
        ]);

        return $cssClasses;
    }

    private function getGridClassesFromXmlConfig(AbstractBlock $block): string
    {
        $grid = $this->config->getGrid();
        $blockAlias = $block->getLayout()->getElementAlias($block->getNameInLayout());
        if (empty($blockAlias)) {
            $blockParts = explode('.', $block->getNameInLayout());
            $blockAlias = array_pop($blockParts);
        }

        if (empty($blockAlias)) {
            return '';
        }

        try {
            $block = $grid->getBlock($blockAlias);
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
