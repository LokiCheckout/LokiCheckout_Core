<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepBackwardButton;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Checkout\StepButton\AbstractStepButtonViewModel;

/**
 * @method CheckoutContext getContext()
 */
class StepBackwardButtonViewModel extends AbstractStepButtonViewModel
{
    public function isAllowRendering(): bool
    {
        $isAllowRendering = parent::isAllowRendering();
        if ($isAllowRendering === false) {
            return false;
        }

        $currentStep = $this->getStepNavigator()->getCurrentStep();
        if ($this->getStepNavigator()->isFirstStep($currentStep)) {
            return false;
        }

        return $this->getStepNavigator()->hasPreviousStep();
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStepBackwardButton';
    }
}
