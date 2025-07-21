<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;
use LokiCheckout\Core\Step\StepNavigator;

class StepProvider
{
    public function __construct(
        private readonly StepNavigator $stepNavigator,
        private readonly LayoutInterface $layout
    ) {
    }

    public function getStep(AbstractBlock $block): string
    {
        $singleStep = $this->getSingleStep();
        if ($singleStep !== null && $singleStep !== '' && $singleStep !== '0') {
            return $singleStep;
        }

        foreach ($this->stepNavigator->getSteps() as $step) {
            $stepElements = $this->findElementNamesByBlockName($step->getComponentName());
            if (in_array($block->getNameInLayout(), $stepElements)) {
                return $step->getCode();
            }
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

    private function findElementNamesByBlockName(string $blockName): array
    {
        static $allElementNames = [];
        if (isset($allElementNames[$blockName]) && is_array($allElementNames[$blockName])) {
            return $allElementNames[$blockName];
        }

        $elementNames[] = $blockName;

        $childNames = $this->layout->getChildNames($blockName);
        foreach ($childNames as $childName) {
            $elementNames = array_merge($elementNames, $this->findElementNamesByBlockName($childName));
        }

        $allElementNames[$blockName] = $elementNames;
        return $elementNames;
    }
}
