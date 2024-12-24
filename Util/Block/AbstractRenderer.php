<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

abstract class AbstractRenderer implements ArgumentInterface
{
    private array $counters = [];

    /**
     * @param AbstractBlock      $block
     * @param AbstractBlock|null $ancestorBlock
     * @param array              $data
     *
     * @return void
     */
    protected function populateBlock(
        AbstractBlock $block,
        ?AbstractBlock $ancestorBlock = null,
        array $data = []
    ): void {
        if (false === $ancestorBlock instanceof AbstractBlock) {
            return;
        }

        $block->setAncestorBlock($ancestorBlock);
        $block->addData($data);

        $viewModel = $ancestorBlock->getViewModel();
        if ($viewModel instanceof ComponentViewModelInterface) {
            $block->setViewModel($viewModel);
        }
    }

    protected function getCounter(AbstractBlock $block): int
    {
        $nameInLayout = $block->getNameInLayout();
        if (isset($this->counters[$nameInLayout])) {
            $this->counters[$nameInLayout]++;
        } else {
            $this->counters[$nameInLayout] = 0;
        }

        return $this->counters[$nameInLayout];
    }

    protected function getAlias(AbstractBlock $block, AbstractBlock $parentBlock): string
    {
        $alias = $block->getAlias();
        if (!empty($alias)) {
            return $alias;
        }

        return 'child'.$this->getCounter($parentBlock);
    }

    protected function setNameInLayout(AbstractBlock $block, AbstractBlock $parentBlock, string $alias = ''): void
    {
        if (empty($alias)) {
            $alias = $this->getAlias($block, $parentBlock);
        }

        $block->setNameInLayout($parentBlock->getNameInLayout().'.'.$alias);
    }
}
