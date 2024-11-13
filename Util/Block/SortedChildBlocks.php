<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class SortedChildBlocks implements ArgumentInterface
{
    public function getChildren(AbstractBlock $parentBlock, int $defaultSortOrder = 99): array
    {
        $childAliasConfiguration = $this->getConfiguration($parentBlock);
        $childNames = $parentBlock->getChildNames();
        $childNameConfiguration = [];
        foreach ($childAliasConfiguration as $childAlias => $sortOrder) {
            $childName = $parentBlock->getLayout()->getChildName($parentBlock->getNameInLayout(), $childAlias);
            if (empty($childName)) {
                continue;
            }

            $childNameConfiguration[$childName] = $sortOrder;
        }

        $children = [];
        $parentBlock->getChildHtml();

        foreach ($childNames as $childName) {
            $childBlock = $parentBlock->getLayout()->getBlock($childName);
            $sortOrder = isset($childNameConfiguration[$childName]) ? $childNameConfiguration[$childName] : $defaultSortOrder;
            $childBlock->setSortOrder($sortOrder);
            $children[] = $childBlock;
        }

        $sortedChildren = $this->sortBlocks($children);
        return $sortedChildren;
    }

    private function sortBlocks(array $blocks): array
    {
        usort($blocks, function(AbstractBlock $blockA, AbstractBlock $blockB) {
            return (int)$blockA->getSortOrder() <=> (int)$blockB->getSortOrder();
        });

        return $blocks;
    }

    private function getConfiguration(AbstractBlock $parentBlock): array
    {
        $sortOrder = $parentBlock->getData('sort_order');
        if (is_array($sortOrder)) {
            return $sortOrder;
        }


        $sortOrderLines = [];
        $i = 0;
        foreach (explode("\n", (string)$sortOrder) as $sortOrderLine) {
            $sortOrderLine = trim($sortOrderLine);
            if (empty($sortOrderLine)) {
                continue;
            }

            $sortOrderLines[$sortOrderLine] = $i;
            $i++;
        }

        return $sortOrderLines;
    }
}
