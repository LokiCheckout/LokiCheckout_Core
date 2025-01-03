<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step\ShippingStep;

use Yireo\LokiCheckout\Component\Checkout\Step\StepContext;
use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiComponents\Component\ComponentViewModel;

/**
 * @method StepContext getContext()
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
        if ((bool)$this->getContext()->getQuote()->isVirtual()) {
            return false;
        }

        return true;
    }

    public function isAccessable(): bool
    {
        return true;
    }

    public function isVisible(): bool
    {
        return $this->getContext()->getStepNavigator()->isCurrentStep($this);
    }

    public function validate(): true|array
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
