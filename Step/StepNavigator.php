<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use RuntimeException;
use Yireo\LokiCheckout\Component\Checkout\Step\StepInterface;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiCheckout\Exception\UnknownStepException;

class StepNavigator implements ArgumentInterface
{
    private StepInterface|false $currentStep = false;
    private array $steps = [];

    public function __construct(
        private readonly XmlConfig $xmlConfig,
        private readonly Config $config,
        private readonly LayoutInterface $layout,
        private readonly CheckoutSession $checkoutSession,
        private readonly FinalStep $finalStep,
        private readonly MessageManager $messageManager,
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

        if ($this->currentStep->validate() === false) {
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
            return;
        }

        $previousStep = $this->getPreviousStep($currentStep);
        $this->setCurrentStepByCode($previousStep->getCode());
    }

    public function validateStep(StepInterface $step): bool
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
     * @return StepInterface[]
     */
    public function getSteps(): array
    {
        if (!empty($this->steps)) {
            return $this->steps;
        }

        $currentThemeName = $this->config->getTheme();
        $theme = $this->xmlConfig->getTheme($currentThemeName);

        /** @var Template $parentBlock */
        $parentBlock = $this->layout->getBlock('loki-checkout.steps');

        $steps = $theme->getSteps();
        foreach ($steps as $step) {
            $stepComponent = $step->getComponent();
            if (false === $stepComponent instanceof StepInterface) {
                continue;
            }

            if (false === $stepComponent->isEnabled()) {
                continue;
            }

            $stepBlockAlias = $step->getName().'-step';

            if ($parentBlock instanceof Template) {
                $stepBlock = $parentBlock->getChildBlock($stepBlockAlias);

                if ($stepBlock instanceof Template) {
                    $stepComponent->setBlock($stepBlock);
                }
            }

            $this->steps[$stepComponent->getCode()] = $stepComponent;
        }

        if (empty($this->steps)) {
            throw new RuntimeException('No steps defined');
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
