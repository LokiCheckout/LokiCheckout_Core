<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Util;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\ObjectManagerInterface;
use Yireo\MagewireCheckout\Exception\UnknownStepException;
use Yireo\MagewireCheckout\Magewire\Checkout\BillingStep;
use Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep;
use Yireo\MagewireCheckout\Magewire\Checkout\StepInterface;

class StepNavigator
{
    private StepInterface|false $currentStep = false;

    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly CheckoutSession $checkoutSession
    ) {
    }

    public function getCurrentStep(): StepInterface
    {
        if (false === $this->currentStep instanceof StepInterface) {
            try {
                $this->setCurrentStepByCode((string)$this->checkoutSession->getCurrentStep());
            } catch(UnknownStepException $unknownStepException) {}
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
        $this->currentStep = $this->createByCode($stepCode);
        $this->checkoutSession->setCurrentStep($this->currentStep->getCode());
        //$this->checkoutSession->setStepData();
    }

    /**
     * @param string $stepCode
     * @return StepInterface
     * @throws UnknownStepException
     */
    public function createByCode(string $stepCode): StepInterface
    {
        foreach ($this->getStepClasses() as $stepClass) {
            $step = $this->objectManager->get($stepClass);
            if ($step->getCode() === $stepCode) {
                return $step;
            }
        }

        throw new UnknownStepException('Unknown step code "'.$stepCode.'"');
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

    public function getSteps(): array
    {
        $steps = [];
        foreach ($this->getStepClasses() as $stepClass) {
            $steps[] = $this->objectManager->get($stepClass);
        }

        return $steps;
    }

    /**
     * @return string[]
     */
    public function getStepClasses(): array
    {
        // @todo: Make this configurable
        return [
            ShippingStep::class,
            BillingStep::class,
        ];
    }
}
