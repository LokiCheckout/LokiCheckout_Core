<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiCheckout\Component\SetterInjection\InjectComponentRegistry;

// @todo: Add integration test
trait StepBehaviour
{
    use InjectComponentRegistry;

    protected string $step = '';

    public function setStep(string $step): void
    {
        $this->step = $step;
    }

    public function getStep(): string
    {
        if (!empty($this->step)) {
            return $this->step;
        }

        $step = $this->getStepNameFromBlock($this->getBlock());
        if (!empty($step)) {
            $this->step = $step;
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
