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

        $cssStyles = array_merge($cssStyles, $this->getDefaultCssStyles());

        $blockDefinition = $this->getBlockDefinitionByBlock($block);
        if ($blockDefinition instanceof Block) {
            $cssStyles = array_merge($cssStyles, $this->getCssStylesFromBlockDefinition($blockDefinition));
        }

        $cssStyles = array_merge($cssStyles, $this->getCssStylesFromBlock($block));

        return $cssStyles;
    }

    private function getDefaultCssStyles(): array
    {
        return [
            '--loki-sort-order' => $this->config->getGridLayout()->getDefaultSortOrder(),
            '--loki-col-span' => $this->config->getGridLayout()->getDefaultColSpan(),
            '--loki-md-col-span' => $this->config->getGridLayout()->getDefaultMdColSpan(),
        ];
    }

    private function getCssStylesFromBlockDefinition(Block $blockDefinition): array
    {
        return [
            '--loki-sort-order' => $blockDefinition->getSortOrder(),
            '--loki-col-span' => $blockDefinition->getColSpan(),
            '--loki-md-col-span' => $blockDefinition->getMdColSpan(),
        ];
    }

    private function getCssStylesFromBlock(AbstractBlock $block): array
    {
        $cssStyles = [];
        if ($block->getSortOrder()) {
            $cssStyles['--loki-sort-order'] = $block->getSortOrder();
        }

        return $cssStyles;
    }

    private function getBlockDefinitionByBlock(AbstractBlock $block): false|Block
    {
        $parentBlock = $block->getParentBlock();
        if (false === $parentBlock instanceof AbstractBlock) {
            return false;
        }

        $grid = $this->config->getGrid($parentBlock);
        $blockAlias = (string)$block->getLayout()->getElementAlias($block->getNameInLayout());
        if (strlen($blockAlias) === 0) {
            $blockParts = explode('.', $block->getNameInLayout());
            $blockAlias = (string)array_pop($blockParts);
        }

        if (strlen($blockAlias) === 0) {
            return false;
        }

        try {
            return $grid->getBlock($blockAlias);
        } catch (BlockNotFoundException) {
            return false;
        }
    }
}
