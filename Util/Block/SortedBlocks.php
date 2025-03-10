<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiCheckout\Exception\BlockNotFoundException;

class SortedBlocks implements ArgumentInterface
{
    public function __construct(
        private Config $config,
        private XmlConfig $xmlConfig,
    ) {
    }

    public function getChildren(
        AbstractBlock $parentBlock
    ): array {
        $gridLayoutName = (string)$this->config->getGridLayout();
        $gridLayout = $this->xmlConfig->getGridLayout($gridLayoutName);

        $childNames = $parentBlock->getChildNames();
        $children = [];

        foreach ($childNames as $childName) {
            $childBlock = $parentBlock->getLayout()->getBlock($childName);
            if (false === $childBlock instanceof AbstractBlock) {
                continue;
            }

            $childAlias = $childBlock->getLayout()->getElementAlias($childName);
            try {
                $block = $gridLayout->getBlock($childAlias);
                $sortOrder = $block->getSortOrder();
            } catch(BlockNotFoundException $exception) {
                $sortOrder = $gridLayout->getDefaultSortOrder();
            }

            $childBlock->setSortOrder($sortOrder);
            $children[] = $childBlock;
        }

        $sortedChildren = $this->sortBlocks($children);

        return $sortedChildren;
    }

    private function sortBlocks(array $blocks): array
    {
        usort($blocks, function (AbstractBlock $blockA, AbstractBlock $blockB) {
            return (int)$blockA->getSortOrder() <=> (int)$blockB->getSortOrder();
        });

        return $blocks;
    }
}
