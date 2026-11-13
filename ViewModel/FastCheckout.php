<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class FastCheckout implements ArgumentInterface
{
    /**
     * @return array{html: string, visible: bool}
     */
    public function renderChildren(AbstractBlock $parentBlock): array
    {
        $layout = $parentBlock->getLayout();
        $html = '';
        $visible = false;

        foreach ($this->getChildBlocks($parentBlock) as $childBlock) {
            $childBlock->setAncestorBlock($parentBlock);
            $childHtml = (string)$childBlock->toHtml();
            $html .= $childHtml."\n";
            $visible = $visible || $this->isVisibleHtml($childBlock, $childHtml);
        }

        foreach ($parentBlock->getChildNames() as $childName) {
            if ($layout->isBlock($childName)) {
                continue;
            }

            $childHtml = (string)$layout->renderElement($childName);
            $html .= $childHtml;
            $visible = $visible || $this->isVisibleHtml(null, $childHtml);
        }

        return ['html' => $html, 'visible' => $visible];
    }

    /**
     * @return AbstractBlock[]
     */
    private function getChildBlocks(AbstractBlock $parentBlock): array
    {
        $layout = $parentBlock->getLayout();
        $childBlocks = [];
        foreach ($parentBlock->getChildNames() as $childName) {
            $childBlock = $layout->getBlock($childName);
            if ($childBlock instanceof AbstractBlock) {
                $childBlocks[] = $childBlock;
            }
        }

        usort($childBlocks, function (AbstractBlock $blockA, AbstractBlock $blockB) {
            return (int)$blockA->getSortOrder() <=> (int)$blockB->getSortOrder();
        });

        return $childBlocks;
    }

    private function isVisibleHtml(?AbstractBlock $block, string $html): bool
    {
        if ($block instanceof AbstractBlock && $block->getNotRendered() === true) {
            return false;
        }

        return trim((string)preg_replace('/<!--.*?-->/s', '', $html)) !== '';
    }
}
