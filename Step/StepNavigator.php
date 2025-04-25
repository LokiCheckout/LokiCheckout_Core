<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiCheckout\Exception\StepNotFoundException;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Exception\UnknownThemeException;
use Yireo\LokiComponents\Component\ComponentRegistry;

class StepNavigator implements ArgumentInterface
{
    private StepViewModelInterface|false $currentStep = false;
    private array $steps = [];

    public function __construct(
        private readonly XmlConfig $xmlConfig,
        private readonly Config $config,
        private readonly CheckoutSession $checkoutSession,
        private readonly FinalStep $finalStep,
        private readonly MessageManager $messageManager,
        private readonly ComponentRegistry $componentRegistry,
        private readonly RequestInterface $request,
    ) {
    }

    public function getCurrentStep(): StepViewModelInterface
    {
        if (false === $this->currentStep instanceof StepViewModelInterface) {
            try {
                $this->setCurrentStepByCode($this->getRequestedStep());
            } catch (UnknownStepException $unknownStepException) {
            }
        }

        if (false === $this->currentStep instanceof StepViewModelInterface) {
            $this->currentStep = $this->getFirstStep();
        }

        if ($this->currentStep->validate() === false) {
            $this->currentStep = $this->getFirstStep();
        }

        return $this->currentStep;
    }

    private function getRequestedStep(): string
    {
        $step = (string)$this->checkoutSession->getCurrentStep();
        if (!empty($step)) {
            return $step;
        }

        return (string)$this->request->getParam('step');
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
            return;
        }

        $previousStep = $this->getPreviousStep($currentStep);
        $this->setCurrentStepByCode($previousStep->getCode());
    }

    public function validateStep(StepViewModelInterface $step): bool
    {
        $validationResult = $step->validate();
        if (true === $validationResult) {
            return true;
        }

        foreach ($validationResult as $error) {
            $this->messageManager->addErrorMessage($error);
        }

        return false;
    }

    public function setNextStep(): bool
    {
        $currentStep = $this->getCurrentStep();
        if (false === $this->validateStep($currentStep)) {
            return false;
        }

        if ($this->isLastStep($currentStep)) {
            $this->finalStep->validate();

            return $this->finalStep->submit();
        }

        $nextStep = $this->getNextStep($currentStep);
        $this->setCurrentStepByCode($nextStep->getCode());

        return true;
    }

    /**
     * @param string $stepCode
     *
     * @return StepViewModelInterface
     * @throws UnknownStepException
     */
    public function getStepByCode(string $stepCode): StepViewModelInterface
    {
        foreach ($this->getSteps() as $step) {
            if ($step->getCode() === $stepCode) {
                return $step;
            }
        }

        throw new UnknownStepException('Unknown step code "'.$stepCode.'"');
    }

    public function isCurrentStep(StepViewModelInterface $step): bool
    {
        return $step->getCode() === $this->getCurrentStep()->getCode();
    }

    public function isFirstStep(StepViewModelInterface $step): bool
    {
        return $step->getCode() === $this->getFirstStep()->getCode();
    }

    public function isLastStep(StepViewModelInterface $step): bool
    {
        return $step->getCode() === $this->getLastStep()->getCode();
    }

    public function getFirstStep(): StepViewModelInterface
    {
        $steps = $this->getSteps();

        return array_shift($steps);
    }

    public function getLastStep(): StepViewModelInterface
    {
        $steps = $this->getSteps();

        return array_pop($steps);
    }

    public function addStep(StepViewModelInterface $step)
    {
        $this->steps[$step->getCode()] = $step;
    }

    /**
     * @return StepViewModelInterface[]
     * @throws StepNotFoundException
     */
    public function getSteps(): array
    {
        if (!empty($this->steps)) {
            return $this->steps;
        }

        $currentThemeName = $this->config->getTheme();

        try {
            $theme = $this->xmlConfig->getTheme($currentThemeName);
        } catch (UnknownThemeException $e) {
            $theme = $this->xmlConfig->getTheme('default');
        }

        $steps = $theme->getSteps();
        foreach ($steps as $step) {
            $stepComponent = $this->componentRegistry->getComponentByName($step->getBlockName());
            $stepViewModel = $stepComponent->getViewModel();

            if (false === $stepViewModel instanceof StepViewModelInterface) {
                continue;
            }

            if (false === $stepViewModel->isEnabled()) {
                continue;
            }

            $this->steps[$stepViewModel->getCode()] = $stepViewModel;
        }

        if (empty($this->steps)) {
            throw new StepNotFoundException('No steps defined');
        }

        return $this->steps;
    }

    public function getNextStep(?StepViewModelInterface $currentStep = null): StepViewModelInterface
    {
        if (false === $currentStep instanceof StepViewModelInterface) {
            $currentStep = $this->getCurrentStep();
        }

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

    public function getPreviousStep(?StepViewModelInterface $currentStep = null): StepViewModelInterface
    {
        if (false === $currentStep instanceof StepViewModelInterface) {
            $currentStep = $this->getCurrentStep();
        }

        $previous = false;
        $steps = $this->getSteps();
        foreach ($steps as $step) {
            if ($step->getCode() === $currentStep->getCode() && $previous instanceof StepViewModelInterface) {
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

    public function reset(): void
    {
        $this->steps = [];
        $this->currentStep = false;
    }
}
