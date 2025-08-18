<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step\ShippingStep;

use LokiCheckout\Core\Component\Checkout\Step\StepViewModelInterface;
use Loki\Components\Component\ComponentViewModel;

/**
 * @method ShippingStepContext getContext()
 */
class ShippingStepViewModel extends ComponentViewModel implements StepViewModelInterface
{
    public function getCode(): string
    {
        return 'shipping';
    }

    public function getLabel(): string
    {
        return 'Shipping';
    }

    public function isEnabled(): bool
    {
        return !(bool)$this->getContext()->getCheckoutState()->getQuote()->isVirtual();
    }

    public function isAccessable(): bool
    {
        return true;
    }

    public function isVisible(): bool
    {
        return $this->getContext()->getStepNavigator()->isCurrentStep($this);
    }

    public function validate(): bool|array
    {
        return $this->getContext()->getStepValidator()->validate();
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStep';
    }

    public function getJsData(): array
    {
        return [
            'visible' => $this->isVisible(),
            'step' => $this->getStep(),
        ];
    }

    public function getStep(): string
    {
        return 'none';
    }
}
