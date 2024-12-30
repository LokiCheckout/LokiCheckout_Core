<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepBackwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepNavigator;

/**
 * @method GenericContext getContext()
 */
class StepBackwardButtonViewModel extends GenericViewModel
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
}
