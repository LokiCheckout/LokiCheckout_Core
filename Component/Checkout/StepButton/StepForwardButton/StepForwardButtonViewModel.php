<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepForwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;

/**
 * @method GenericContext getContext()
 */
class StepForwardButtonViewModel extends GenericViewModel
{
    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStepForwardButton';
    }

    public function getJsData(): array
    {
        return [
            'currentStep' => $this->getContext()->getStepNavigator()->getCurrentStep()->getCode(),
            'redirectUrl' => $this->getContext()->getCheckoutSession()->getLokiRedirectUrl(),
        ];
    }

    public function getStep(): string
    {
        return 'none';
    }
}
