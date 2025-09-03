<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Block\CssStyleParser;

use LokiCheckout\Core\Config\XmlConfig\Value\GridLayout\Grid\Block;
use Magento\Framework\View\Element\AbstractBlock;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Exception\BlockNotFoundException;
use Loki\CssUtils\Util\CssStyleParser\CssStyleParserInterface;

class GridStyleParser implements CssStyleParserInterface
{
    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function parse(array $cssStyles, AbstractBlock $block): array
    {
        $parentBlock = $block->getParentBlock();
        if (false === $parentBlock instanceof AbstractBlock) {
            return $cssStyles;
        }

        if (true !== $parentBlock->getGrid()) {
            return $cssStyles;
        }

        $blockDefinition = $this->getBlockDefinitionByBlock($block);
        if (false === $blockDefinition instanceof Block) {
            $cssStyles['--loki-order'] = $this->config->getGridLayout()->getDefaultSortOrder();
            $cssStyles['--loki-col-span'] = $this->config->getGridLayout()->getDefaultColSpan();
            $cssStyles['--loki-md-col-span'] = $this->config->getGridLayout()->getDefaultMdColSpan();

            return $cssStyles;
        }

        $cssStyles['--loki-order'] = $blockDefinition->getSortOrder();
        $cssStyles['--loki-col-span'] = $blockDefinition->getColSpan();
        $cssStyles['--loki-md-col-span'] = $blockDefinition->getMdColSpan();

        return $cssStyles;
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
}
