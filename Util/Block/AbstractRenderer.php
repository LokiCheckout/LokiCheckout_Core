<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

abstract class AbstractRenderer implements ArgumentInterface
{
    protected ?AbstractBlock $ancestorBlock = null;

    private array $counters = [];

    public function setAncestorBlock(AbstractBlock $block): AbstractRenderer
    {
        $this->ancestorBlock = $block;
        return $this;
    }

    /**
     * @param AbstractBlock $block
     * @param AbstractBlock|null $ancestorBlock
     * @param array $data
     *
     * @return void
     */
    protected function populateBlock(
        AbstractBlock $block,
        array $data = []
    ): void {
        if (false === $this->ancestorBlock instanceof AbstractBlock) {
            return;
        }

        $block->setAncestorBlock($this->ancestorBlock);
        $block->setUniqId($this->getUniqId($block));
        $block->addData($data);

        $viewModel = $this->ancestorBlock->getViewModel();
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

    protected function getAlias(AbstractBlock $block): string
    {
        $alias = $block->getAlias();
        if (!empty($alias)) {
            return $alias;
        }

        if ($this->ancestorBlock instanceof AbstractBlock) {
            return 'child'.$this->getCounter($this->ancestorBlock);
        }

        return '';
    }

    protected function setNameInLayout(AbstractBlock $block, string $alias = ''): void
    {
        if (empty($alias)) {
            $alias = $this->getAlias($block);
        }

        $block->setNameInLayout($this->ancestorBlock->getNameInLayout().'.'.$alias);
    }

    protected function getUniqId(AbstractBlock $block): string
    {
        $ancestorId = $this->ancestorBlock->getNameInLayout();
        $blockParts = explode('.', $block->getNameInLayout());
        return $ancestorId.'-'.array_pop($blockParts);
    }
}
