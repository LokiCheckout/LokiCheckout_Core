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
