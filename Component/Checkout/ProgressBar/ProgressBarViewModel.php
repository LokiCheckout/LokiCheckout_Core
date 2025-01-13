<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\ProgressBar;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method CheckoutContext getContext()
 */
class ProgressBarViewModel extends ComponentViewModel
{
    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutSteps';
    }

    public function getStep(): string
    {
        return 'none';
    }

    public function getJsData(): array
    {
        return [
            'step' => $this->getStep(),
            'currentStep' => $this->getContext()->getStepNavigator()->getCurrentStep()->getCode(),
        ];
    }
}
