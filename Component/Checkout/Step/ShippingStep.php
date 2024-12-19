<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Step;

use Yireo\LokiCheckout\Component\Generic;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ShippingStep extends Generic implements StepInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ValidatorInterface $stepValidator,
        private readonly StepNavigator $stepNavigator,
    ) {
    }

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
        if ((bool)$this->checkoutState->getQuote()->isVirtual()) {
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
        return $this->stepNavigator->isCurrentStep($this);
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
