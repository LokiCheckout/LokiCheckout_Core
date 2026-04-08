<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepBackwardButton;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Exception\UnknownStepException;
use LokiCheckout\Core\Step\StepNavigator;
use Loki\Field\Util\ButtonInterface;

/**
 * @method CheckoutContext getContext()
 */
class StepBackwardButtonViewModel extends CheckoutViewModel implements ButtonInterface
{
    public function isAllowRendering(): bool
    {
        $currentStep = $this->getStepNavigator()->getCurrentStep();
        if ($this->getStepNavigator()->isFirstStep($currentStep)) {
            return false;
        }

        $stepCode = $this->getBlock()->getStepCode();

        if (!empty($stepCode)) {
            $step = $this->getContext()->getStepNavigator()->getStepByCode($stepCode);
            if (false === $this->getContext()->getStepNavigator()->isCurrentStep($step)) {
                return false;
            }
        }

        return $this->getStepNavigator()->hasPreviousStep();
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStepBackwardButton';
    }

    public function getStep(): string
    {
        return 'none';
    }

    public function getButtonLabel(): string
    {
        return $this->getBlock()->getButtonLabel();
    }
}
