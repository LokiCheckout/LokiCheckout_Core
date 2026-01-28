<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Util\Component;

use LokiCheckout\Core\Exception\StepNotFoundException;
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
        if (!empty($singleStep)) {
            return $singleStep;
        }

        try {
            return $this->findStepNameViaStepBlocks($block->getNameInLayout());
        } catch (StepNotFoundException $e) {}

        try {
            return $this->findStepNameByChildBlock($block->getNameInLayout());
        } catch (StepNotFoundException $e) {}

        return 'none';
    }

    private function getStepBlockNames(): array
    {
        static $stepBlockNames = null;
        if (is_array($stepBlockNames)) {
            return $stepBlockNames;
        }

        $stepBlockNames = [];
        foreach ($this->stepNavigator->getSteps() as $step) {
            $stepBlockNames[$step->getCode()] = $step->getComponentName();
        }

        return $stepBlockNames;
    }

    /**
     * @throws StepNotFoundException
     */
    private function findStepNameViaStepBlocks(string $blockName): ?string
    {
        $stepBlockNames = $this->getStepBlockNames();

        foreach ($stepBlockNames as $stepCode => $stepBlockName) {
            $stepElements = $this->findElementNamesByBlockName($stepBlockName);
            if (in_array($blockName, $stepElements)) {
                return $stepCode;
            }
        }

        throw new StepNotFoundException('Step block not found for block ' . $blockName);
    }

    private function findStepNameByChildBlock(string $blockName): string
    {
        $stepBlockNames = $this->getStepBlockNames();

        $current = $blockName;
        while (true) {
            $parentName = $this->layout->getParentName($current);
            if (empty($parentName)) {
                $currentBlock = $this->layout->getBlock($current);
                if ($currentBlock instanceof AbstractBlock) {
                    $parentName = $currentBlock->getAncestorBlock()?->getNameInLayout();
                }
            }

            if (empty($parentName)) {
                throw new StepNotFoundException('Step block not found for block ' . $blockName);
            }

            $parentStep = array_search($parentName, $stepBlockNames, true);
            if ($parentStep !== false) {
                return $parentStep;
            }

            $current = $parentName;
        }
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

        $elementNames = [];
        $elementNames[] = $blockName;

        $childNames = $this->layout->getChildNames($blockName);
        foreach ($childNames as $childName) {
            $elementNames = array_merge($elementNames, $this->findElementNamesByBlockName($childName));
        }

        $allElementNames[$blockName] = $elementNames;

        return $elementNames;
    }
}
