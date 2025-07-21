<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\ProgressBar;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Components\Component\ComponentViewModel;

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
