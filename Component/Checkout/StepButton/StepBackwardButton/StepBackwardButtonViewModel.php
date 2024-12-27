<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepBackwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;
use Yireo\LokiCheckout\Component\Checkout\StepButton\StepButtonContext;
use Yireo\LokiCheckout\Step\StepNavigator;

/**
 * @method StepButtonContext getContext()
 */
class StepBackwardButtonViewModel extends GenericViewModel
{
    public function isAllowRendering(): bool
    {
        $previousStep = $this->getStepNavigator()->getPreviousStep();
        if ($this->getStepNavigator()->isFirstStep($previousStep)) {
            return false;
        }

        return true;
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->stepNavigator;
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStepBackwardButton';
    }
}
