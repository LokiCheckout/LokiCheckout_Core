<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Step\StepComponentInterface;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class ShippingStep extends GenericComponent implements StepComponentInterface
{
    use AlpineDataBehaviour;

    protected $listeners = [
        'afterChangeStep' => 'refresh',
        'afterSaveShippingStep' => 'refresh',
    ];

    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ValidatorInterface $stepValidator,
        private readonly StepNavigator $stepNavigator,
    ) {
    }

    public function boot(): void
    {
        if (false === $this->stepNavigator->isCurrentStep($this)) {
            $this->allowRendering = false;
        }

        // @todo: Double-check for virtual products
        if ((bool)$this->checkoutState->getQuote()->isVirtual()) {
            $this->allowRendering = false;
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

    public function getStep(): string
    {
        return 'any';
    }
}
