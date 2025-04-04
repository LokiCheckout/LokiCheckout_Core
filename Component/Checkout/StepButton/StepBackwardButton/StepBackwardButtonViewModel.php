<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepBackwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiFieldComponents\Util\ButtonInterface;

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

        try {
            $previousStep = $this->getStepNavigator()->getPreviousStep();
        } catch (UnknownStepException $e) {
            return false;
        }

        return true;
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
