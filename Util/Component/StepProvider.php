<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use RuntimeException;
use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiComponents\Component\ComponentRegistry;
use Yireo\LokiComponents\Component\ComponentViewModelInterface;

class StepProvider
{
    public function __construct(
        private readonly ComponentRegistry $componentRegistry,
        private readonly StepNavigator $stepNavigator
    ) {
    }

    public function getStep(AbstractBlock $block): string
    {
        $singleStep = $this->getSingleStep();
        if (!empty($singleStep)) {
            return $singleStep;
        }

        $step = $this->getStepNameFromBlock($block);
        if (!empty($step)) {
            return $step;
        }

        return 'none';
    }

    private function getSingleStep(): ?string
    {
        if (count($this->stepNavigator->getSteps()) > 1) {
            return null;
        }

        return $this->stepNavigator->getFirstStep()->getCode();
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
