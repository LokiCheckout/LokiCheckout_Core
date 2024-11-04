<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Exception\UnknownStepException;

class StepNavigator implements ArgumentInterface
{
    private StepInterface|false $currentStep = false;
    private array $steps = [];

    public function __construct(
        private readonly LayoutInterface $layout,
        private readonly CheckoutSession $checkoutSession,
        private readonly FinalStep $finalStep
    ) {
    }

    public function getCurrentStep(): StepInterface
    {
        if (false === $this->currentStep instanceof StepInterface) {
            try {
                $this->setCurrentStepByCode((string)$this->checkoutSession->getCurrentStep());
            } catch (UnknownStepException $unknownStepException) {
            }
        }

        if (false === $this->currentStep instanceof StepInterface) {
            $this->currentStep = $this->getFirstStep();
        }

        return $this->currentStep;
    }

    /**
     * @param string $stepCode
     * @return void
     * @throws UnknownStepException
     */
    public function setCurrentStepByCode(string $stepCode)
    {
        $this->currentStep = $this->getStepByCode($stepCode);
        $this->checkoutSession->setCurrentStep($this->currentStep->getCode());
    }

    public function setPreviousStep()
    {
        $currentStep = $this->getCurrentStep();
        if ($this->isFirstStep($currentStep)) {
            throw new UnknownStepException('This is already the first step');
        }

        $previousStep = $this->getPreviousStep($currentStep);
        $this->setCurrentStepByCode($previousStep->getCode());
    }

    public function validateStep(StepInterface $step, Component $source): bool
    {
        $validationResult = $step->validate();
        if (true === $validationResult) {
            return true;
        }

        foreach ($validationResult as $error) {
            $source->dispatchErrorMessage($error);
        }

        return false;
    }

    public function setNextStep(Component $source): bool
    {
        $currentStep = $this->getCurrentStep();
        if (false === $this->validateStep($currentStep, $source)) {
            return false;
        }

        if ($this->isLastStep($currentStep)) {
            $this->finalStep->validate();
            return $this->finalStep->submit($source);
        }

        $nextStep = $this->getNextStep($currentStep);
        $this->setCurrentStepByCode($nextStep->getCode());
        return true;
        //$this->checkoutSession->setStepData();
    }

    /**
     * @param string $stepCode
     * @return StepInterface
     * @throws UnknownStepException
     */
    public function getStepByCode(string $stepCode): StepInterface
    {
        foreach ($this->getSteps() as $step) {
            if ($step->getCode() === $stepCode) {
                return $step;
            }
        }

        throw new UnknownStepException('Unknown step code "'.$stepCode.'"');
    }

    public function isCurrentStep(StepInterface $step): bool
    {
        return $step->getCode() === $this->getCurrentStep()->getCode();
    }

    public function isFirstStep(StepInterface $step): bool
    {
        return $step->getCode() === $this->getFirstStep()->getCode();
    }

    public function isLastStep(StepInterface $step): bool
    {
        return $step->getCode() === $this->getLastStep()->getCode();
    }

    public function getFirstStep(): StepInterface
    {
        $steps = $this->getSteps();

        return array_shift($steps);
    }

    public function getLastStep(): StepInterface
    {
        $steps = $this->getSteps();

        return array_pop($steps);
    }

    public function addStep(StepInterface $step)
    {
        $this->steps[$step->getCode()] = $step;
    }

    /**
     * @return StepInterface|Component[]
     */
    public function getSteps(): array
    {
        if (!empty($this->steps)) {
            return $this->steps;
        }

        /** @var Template $stepsBlock */
        $stepsBlock = $this->layout->getBlock('loki.checkout.steps');
        foreach ($stepsBlock->getChildNames() as $childName) {
            $childBlock = $this->layout->getBlock($childName);
            if (false === $childBlock instanceof Template) {
                continue;
            }

            $magewire = $childBlock->getMagewire();
            if (false === $magewire instanceof StepInterface) {
                continue;
            }

            $this->steps[$magewire->getCode()] = $magewire;
        }

        return $this->steps;
    }

    public function getNextStep(StepInterface $currentStep): StepInterface
    {
        $returnNextStep = false;
        foreach ($this->getSteps() as $step) {
            if ($returnNextStep) {
                return $step;
            }

            if ($step->getCode() === $currentStep->getCode()) {
                $returnNextStep = true;
            }
        }

        throw new UnknownStepException('No next step to "'.$currentStep->getCode().'"');
    }

    public function getPreviousStep(StepInterface $currentStep): StepInterface
    {
        $previous = false;
        $steps = $this->getSteps();
        foreach ($steps as $step) {
            if ($step->getCode() === $currentStep->getCode() && $previous instanceof StepInterface) {
                return $previous;
            }

            $previous = $step;
        }

        throw new UnknownStepException('No previous step to "'.$currentStep->getCode().'"');
    }

    public function getFinalStep(): FinalStep
    {
        return $this->finalStep;
    }
}
