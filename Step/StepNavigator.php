<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Config\XmlConfig;
use Yireo\LokiCheckout\Exception\UnknownStepException;

class StepNavigator implements ArgumentInterface
{
    private StepComponentInterface|false $currentStep = false;
    private array $steps = [];

    public function __construct(
        private readonly XmlConfig $xmlConfig,
        private readonly Config $config,
        private readonly LayoutInterface $layout,
        private readonly CheckoutSession $checkoutSession,
        private readonly FinalStep $finalStep,
    ) {
    }

    public function getCurrentStep(): StepComponentInterface
    {
        if (false === $this->currentStep instanceof StepComponentInterface) {
            try {
                $this->setCurrentStepByCode((string)$this->checkoutSession->getCurrentStep());
            } catch (UnknownStepException $unknownStepException) {
            }
        }

        if (false === $this->currentStep instanceof StepComponentInterface) {
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
            throw new UnknownStepException('This is already the first step');
        }

        $previousStep = $this->getPreviousStep($currentStep);
        $this->setCurrentStepByCode($previousStep->getCode());
    }

    public function validateStep(StepComponentInterface $step, Component $source): bool
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

        // @todo: $this->checkoutSession->setStepData();
        return true;
    }

    /**
     * @param string $stepCode
     * @return StepComponentInterface
     * @throws UnknownStepException
     */
    public function getStepByCode(string $stepCode): StepComponentInterface
    {
        foreach ($this->getSteps() as $step) {
            if ($step->getCode() === $stepCode) {
                return $step;
            }
        }

        throw new UnknownStepException('Unknown step code "'.$stepCode.'"');
    }

    public function isCurrentStep(StepComponentInterface $step): bool
    {
        return $step->getCode() === $this->getCurrentStep()->getCode();
    }

    public function isFirstStep(StepComponentInterface $step): bool
    {
        return $step->getCode() === $this->getFirstStep()->getCode();
    }

    public function isLastStep(StepComponentInterface $step): bool
    {
        return $step->getCode() === $this->getLastStep()->getCode();
    }

    public function getFirstStep(): StepComponentInterface
    {
        $steps = $this->getSteps();
        if (empty($steps)) {
            throw new \RuntimeException('No steps defined');
        }

        return array_shift($steps);
    }

    public function getLastStep(): StepComponentInterface
    {
        $steps = $this->getSteps();
        if (empty($steps)) {
            throw new \RuntimeException('No steps defined');
        }

        return array_pop($steps);
    }

    public function addStep(StepComponentInterface $step)
    {
        $this->steps[$step->getCode()] = $step;
    }

    /**
     * @return StepComponentInterface[]|Component[]
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
            if (false === $stepComponent instanceof StepComponentInterface) {
                continue;
            }

            if (false === $stepComponent->isEnabled()) {
                continue;
            }

            $stepBlockAlias = $step->getName().'-step';

            if ($parentBlock instanceof Template) {
                $stepBlock = $parentBlock->getChildBlock($stepBlockAlias);
                $stepComponent->setParent($stepBlock);
            }

            $this->steps[$stepComponent->getCode()] = $stepComponent;
        }

        return $this->steps;
    }

    public function getNextStep(StepComponentInterface $currentStep): StepComponentInterface
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

    public function getPreviousStep(StepComponentInterface $currentStep): StepComponentInterface
    {
        $previous = false;
        $steps = $this->getSteps();
        foreach ($steps as $step) {
            if ($step->getCode() === $currentStep->getCode() && $previous instanceof StepComponentInterface) {
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
