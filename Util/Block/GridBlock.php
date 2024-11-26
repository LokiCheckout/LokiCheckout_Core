<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class GridBlock implements ArgumentInterface
{
    public function getChildren(
        AbstractBlock $parentBlock,
        int $defaultSortOrder = 99,
        array $defaultCssClasses = []
    ): array {
        $gridConfiguration = $this->getGridConfiguration($parentBlock);
        $childNames = $parentBlock->getChildNames();
        $children = [];
        $parentBlock->getChildHtml();

        foreach ($childNames as $childName) {
            $childBlock = $parentBlock->getLayout()->getBlock($childName);
            $childAlias = $childBlock->getLayout()->getElementAlias($childName);
            if (false === $childBlock instanceof AbstractBlock) {
                continue;
            }

            if (isset($gridConfiguration[$childAlias])) {
                $sortOrder = $gridConfiguration[$childAlias]['sortOrder'];
                $gridCssClasses =  ['block' => ['col-span' => $gridConfiguration[$childAlias]['gridCssClasses']]];
            } else {
                $gridCssClasses = $defaultCssClasses;
                $sortOrder = $defaultSortOrder;
            }

            $cssClasses = (array)$childBlock->getCssClasses();
            $childBlock->setCssClasses(array_merge_recursive($cssClasses,$gridCssClasses));
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

    private function getGridConfiguration(AbstractBlock $parentBlock): array
    {
        $gridLines = (array)$parentBlock->getData('grid');
        if (empty($gridLines)) {
            return [];
        }

        $gridConfiguration = [];
        $sortOrder = 0;
        foreach ($gridLines as $blockAlias => $gridCssClasses) {
            $gridConfiguration[$blockAlias] = [
                'sortOrder' => $sortOrder,
                'gridCssClasses' => $gridCssClasses,
            ];

            $sortOrder++;
        }

        return $gridConfiguration;
    }
}
