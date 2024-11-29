<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Step\StepComponentInterface;

// @todo: Add integration test
trait StepBehaviour
{
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

        $block = $this->getParent();
        if (false === $block instanceof AbstractBlock) {
            return 'unknown';
        }

        $step = $this->getStepNameFromBlock($block);
        if (!empty($step)) {
            return $step;
        }

        return 'unknown';
    }

    private function getStepNameFromBlock(AbstractBlock $block): string
    {
        if ($block->getMagewire() instanceof StepComponentInterface) {
            return $block->getMagewire()->getCode();
        }

        $parentBlock = $block->getParentBlock();
        if (false === $parentBlock instanceof AbstractBlock) {
            return '';
        }

        return $this->getStepNameFromBlock($parentBlock);
    }
}
