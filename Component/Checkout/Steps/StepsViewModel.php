<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Steps;

use Loki\Components\Attribute\JsProperty;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Components\Component\ComponentViewModel;

/**
 * @method CheckoutContext getContext()
 */
class StepsViewModel extends ComponentViewModel
{
    #[JsProperty('currentStep')]
    public function getCurrentStep(): string
    {
        return $this->getContext()->getStepNavigator()->getCurrentStep()->getCode();
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiFieldComponent';
    }
}
