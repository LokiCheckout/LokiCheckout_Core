<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use RuntimeException;
use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

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

        return 'none';
    }

    private function getStepNameFromBlock(AbstractBlock $block): string
    {
        $viewModel = $this->getViewModelFromBlock($block);
        if ($viewModel instanceof StepViewModelInterface) {
            return $viewModel->getCode();
        }

        $parentBlock = $block->getParentBlock();
        if (false === $parentBlock instanceof AbstractBlock) {
            return '';
        }

        return $this->getStepNameFromBlock($parentBlock);
    }

    private function getViewModelFromBlock(AbstractBlock $block): ?ComponentViewModelInterface
    {
        $viewModel = $block->getViewModel();
        if ($viewModel instanceof ComponentViewModelInterface) {
            return $viewModel;
        }

        try {
            $component = $this->componentRegistry->getComponentFromBlock($block);
            return $component->getViewModel();
        } catch (RuntimeException $e) {
        }

        return null;
    }
}
