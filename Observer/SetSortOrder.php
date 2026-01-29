<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use LokiCheckout\Core\Config\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;

class SetSortOrder implements ObserverInterface
{
    public function __construct(
        private readonly Config $config,
        private LayoutInterface $layout
    ) {
    }

    public function execute(Observer $observer)
    {
        /** @var AbstractBlock $parentBlock */
        $parentBlock = $observer->getEvent()->getBlock();
        if ($parentBlock->getGrid() !== true) {
            return;
        }

        $childNames = $parentBlock->getChildNames();
        if (false === is_array($childNames) || count($childNames) <= 1) {
            return;
        }

        $grid = $this->config->getGrid();
        foreach ($grid->getBlocks() as $blockDefinition) {
            $childBlock = $this->layout->getChildBlock($parentBlock->getNameInLayout(), $blockDefinition->getAlias());
            if (false === $childBlock instanceof AbstractBlock) {
                continue;
            }

            $layoutSortOrder = $childBlock->getSortOrder();
            if (is_numeric($layoutSortOrder)) {
                continue;
            }

            $childBlock->setSortOrder($blockDefinition->getSortOrder());
        }
    }
}
