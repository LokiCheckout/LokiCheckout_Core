<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Block\CssClassParser;

use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid\Block;
use LokiCheckout\Core\ViewModel\CssVariables;
use Magento\Framework\View\Element\AbstractBlock;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Exception\BlockNotFoundException;
use Loki\Components\Util\Block\CssClassParser\CssClassParserInterface;

class GridClassParser implements CssClassParserInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly CssVariables $cssVariables,
    ) {
    }

    public function parse(array $cssClasses, string $scope, AbstractBlock $block): array
    {
        if ($scope !== 'block') {
            return $cssClasses;
        }

        $gridClasses = $this->getGridClassesFromXmlConfig($block);
        if ($gridClasses === '') {
            $gridClasses = $this->getDefaultGridClasses($block);
        }

        $sortOrder = $this->getSortOrderFromXmlConfig($block);
        $sortOrderClass = 'checkout-field-order-'.$sortOrder;
        $this->cssVariables->set('checkout-field-order-'.$sortOrder, 'order: '.$sortOrder);

        $cssClasses = array_merge_recursive($cssClasses, [
            'grid' => $gridClasses,
            'sortOrder' => $sortOrderClass,
        ]);

        return $cssClasses;
    }

    private function getSortOrderFromXmlConfig(AbstractBlock $block): int
    {
        $blockDefinition = $this->getBlockDefinitionByBlock($block);
        if (false === $blockDefinition instanceof Block) {
            return 99;
        }

        return $blockDefinition->getSortOrder();
    }

    private function getGridClassesFromXmlConfig(AbstractBlock $block): string
    {
        $blockDefinition = $this->getBlockDefinitionByBlock($block);
        if (false === $blockDefinition instanceof Block) {
            return '';
        }

        return $blockDefinition->getGridClass();
    }

    private function getBlockDefinitionByBlock(AbstractBlock $block): false|Block
    {
        $grid = $this->config->getGrid();
        $blockAlias = $block->getLayout()->getElementAlias($block->getNameInLayout());
        if (empty($blockAlias)) {
            $blockParts = explode('.', $block->getNameInLayout());
            $blockAlias = array_pop($blockParts);
        }

        if (empty($blockAlias)) {
            return false;
        }

        try {
            return $grid->getBlock($blockAlias);
        } catch (BlockNotFoundException) {
            return false;
        }
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
