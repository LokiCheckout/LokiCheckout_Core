<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Steps;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Components\Component\ComponentViewModel;

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
