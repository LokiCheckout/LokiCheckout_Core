<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Steps;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method CheckoutContext getContext()
 */
class StepsViewModel extends ComponentViewModel
{
    public function getJsData(): array
    {
        return [
            'currentStep' => $this->getContext()->getStepNavigator()->getCurrentStep()->getCode()
        ];
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiFieldComponent';
    }
}
