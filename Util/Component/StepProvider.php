<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiComponents\Component\ComponentRegistry;

class StepProvider
{
    public function __construct(
        private readonly ComponentRegistry $componentRegistry
    ) {
    }

    public function getStep(AbstractBlock $block): string
    {
        $step = $this->getStepNameFromBlock($block);
        if (!empty($step)) {
            return $step;
        }

        return 'unknown';
    }

    private function getStepNameFromBlock(AbstractBlock $block): string
    {
        try {
            $component = $this->componentRegistry->getComponentFromBlock($block);
            $viewModel = $component->getViewModel();
        } catch (\RuntimeException $e) {
            $viewModel = null;
        }

        if ($viewModel instanceof StepViewModelInterface) {
            return $viewModel->getCode();
        }

        $parentBlock = $block->getParentBlock();
        if (false === $parentBlock instanceof AbstractBlock) {
            return '';
        }

        return $this->getStepNameFromBlock($parentBlock);
    }
}
