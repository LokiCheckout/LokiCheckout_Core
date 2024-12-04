<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Step\StepComponentInterface;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ShippingStep extends GenericComponent implements StepComponentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ValidatorInterface $stepValidator,
    ) {
    }

    public function boot(): void
    {
        // @todo: Double-check for virtual products
        if ((bool)$this->checkoutState->getQuote()->isVirtual()) {
            $this->visible = false;
        }

        parent::boot();
    }

    public function getCode(): string
    {
        return 'shipping';
    }

    public function getLabel(): string
    {
        return 'Shipping';
    }

    // @todo: Is this still needed?
    public function isEnabled(): bool
    {
        return true;
    }

    // @todo: Is this still needed?
    public function isAccessable(): bool
    {
        return true;
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
