<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Checkout\Step\StepViewModelInterface;
use Loki\Components\Component\ComponentViewModel;
use LokiCheckout\Core\Step\StepNavigator;

/**
 * @method CheckoutContext getContext()
 */
abstract class AbstractStepViewModel extends ComponentViewModel implements StepViewModelInterface
{
    public function __construct(
        private readonly StepNavigator $stepNavigator,
    ) {
    }

    abstract public function getCode(): string;

    abstract public function getLabel(): string;

    public function isEnabled(): bool
    {
        return true;
    }

    public function isAccessable(): bool
    {
        foreach ($this->stepNavigator->getPreviousSteps($this) as $previousStep) {
            if (true !== $previousStep->validate()) {
                return false;
            }
        }

        return true;
    }

    public function isVisible(): bool
    {
        return $this->stepNavigator->isCurrentStep($this);
    }

    public function validate(): bool|array
    {
        return true;
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStep';
    }

    public function getJsData(): array
    {
        return [
            'visible' => $this->isVisible(),
            'step'    => $this->getStep(),
        ];
    }

    public function getStep(): string
    {
        return 'none';
    }
}
