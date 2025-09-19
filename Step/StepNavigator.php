<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\Component\Checkout\Step\StepViewModelInterface;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Config\XmlConfig;
use LokiCheckout\Core\Exception\StepNotFoundException;
use LokiCheckout\Core\Exception\UnknownStepException;
use LokiCheckout\Core\Exception\UnknownThemeException;
use Loki\Components\Component\ComponentRegistry;

class StepNavigator implements ArgumentInterface
{
    private StepViewModelInterface|false $currentStep = false;
    private ?array $steps = null;

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
        if ($this->currentStep instanceof StepViewModelInterface) {
            return $this->currentStep;
        }

        $requestedStepCode = $this->getRequestedStepCode();

        try {
            $requestedStep = $this->getStepByCode($requestedStepCode);
            if ($requestedStep->isEnabled() && $requestedStep->isAccessable()) {
                $this->currentStep = $requestedStep;
                return $this->currentStep;
            }
        } catch(UnknownStepException $e) {
        }

        $this->reset();
        foreach ($this->getSteps() as $step) {
            if ($step->isEnabled() && $step->isAccessable()) {
                return $step;
            }
        }

        /*
        // @tood: When should this be used?
        $result = $this->currentStep->validate();
        if (is_array($result)) {
            foreach ($result as $error) {
                $this->messageManager->addErrorMessage($error);
            }
        }
        */

        throw new StepNotFoundException('No step found with request "'.$requestedStepCode.'". Available steps: '.implode(',' , array_keys($this->getUnsortedSteps())));
    }

    private function getRequestedStepCode(): string
    {
        $step = (string)$this->checkoutSession->getCurrentStep();
        if (strlen($step) > 0) {
            return $step;
        }

        return (string)$this->request->getParam('step');
    }

    /**
     * @param StepViewModelInterface $step
     * @return void
     * @throws UnknownStepException
     */
    public function setCurrentStep(StepViewModelInterface $step): void
    {
        if (false === $step->isEnabled() || false === $step->isAccessable()) {
            return;
        }

        $this->currentStep = $step;
        $this->checkoutSession->setCurrentStep($step->getCode());
    }

    /**
     * @param string $stepCode
     * @return void
     * @throws UnknownStepException
     */
    public function setCurrentStepByCode(string $stepCode): void
    {
        $step = $this->getStepByCode($stepCode);
        $this->setCurrentStep($step);
    }

    public function setPreviousStep(): void
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
            $rt = $this->finalStep->validate();
            if (is_array($rt)) {
                foreach ($rt as $error) {
                    $this->messageManager->addErrorMessage($error);
                }

                return false;
            }

            return $this->finalStep->submit();
        }

        $nextStep = $this->getNextStep($currentStep);
        $this->setCurrentStep($nextStep);

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
     * @return string[]
     */
    public function getStepCodes(): array
    {
        return array_keys($this->getUnsortedSteps());
    }

    /**
     * @return StepViewModelInterface[]
     * @throws StepNotFoundException
     */
    public function getUnsortedSteps(): array
    {
        if (is_array($this->steps) && !empty($steps)) {
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

        if (count($this->steps) === 0) {
            throw new StepNotFoundException('No steps defined');
        }

        return $this->steps;
    }

    public function getSteps(): array
    {
        $steps = $this->getUnsortedSteps();

        usort($steps, function (StepViewModelInterface $stepA, StepViewModelInterface $stepB) {
            if (false === $stepA->hasBlock() || false === $stepB->hasBlock()) {
                return 0;
            }

            return (int)$stepA->getBlock()->getSortOrder() <=> (int)$stepB->getBlock()->getSortOrder();
        });

        return $steps;
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
        $this->steps = null;
        $this->currentStep = false;
    }
}
