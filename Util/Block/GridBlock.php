<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiCheckout\Exception\BlockNotFoundException;

class GridBlock implements ArgumentInterface
{
    public function __construct(
        private XmlConfig $xmlConfig,
    ) {
    }

    public function getChildren(
        AbstractBlock $parentBlock
    ): array {
        $gridLayoutName = (string)$parentBlock->getGridLayout();
        if (empty($gridLayoutName)) {
            $gridLayoutName = 'default_grid';
        }

        $gridLayout = $this->xmlConfig->getGridLayout($gridLayoutName);

        $childNames = $parentBlock->getChildNames();
        $children = [];

        $parentBlock->getChildHtml();

        foreach ($childNames as $childName) {
            $childBlock = $parentBlock->getLayout()->getBlock($childName);
            if (false === $childBlock instanceof AbstractBlock) {
                continue;
            }

            $childAlias = $childBlock->getLayout()->getElementAlias($childName);
            try {
                $block = $gridLayout->getBlock($childAlias);
                $gridClass = $block->getGridClass();
                $sortOrder = $block->getSortOrder();
            } catch(BlockNotFoundException $exception) {
                //echo $exception->getMessage(); @todo: Handle this differently
                $gridClass = $gridLayout->getDefaultGridClass();
                $sortOrder = $gridLayout->getDefaultSortOrder();
            }

            $cssClasses = (array)$childBlock->getCssClasses();
            $childBlock->setSortOrder($sortOrder);
            $childBlock->setCssClasses(array_merge_recursive($cssClasses, [
                'block' => [
                    'grid' => $gridClass
                ],
            ]));

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
