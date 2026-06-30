<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step;

use Loki\Components\Attribute\JsProperty;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Components\Component\ComponentViewModel;
use LokiCheckout\Core\Step\StepNavigator;

/**
 * @method CheckoutContext getContext()
 */
abstract class AbstractStepViewModel extends ComponentViewModel implements StepViewModelInterface
{
    protected bool $valid = true;
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

    #[JsProperty(name: 'visible')]
    public function isVisible(): bool
    {
        return $this->stepNavigator->isCurrentStep($this);
    }

    public function validate(): bool|array
    {
        return true;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStep';
    }

    #[JsProperty(name: 'step')]
    public function getStep(): string
    {
        return 'none';
    }
}
