<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Step\StepInterface;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class ShippingStep extends Component implements StepInterface
{
    public function __construct(
        private readonly ShippingAddressState $shippingAddressState,
        private readonly ValidatorInterface $stepValidator,
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
        return true;
    }

    public function isAccessable(): bool
    {
        return true;
    }

    public function isVisible(): bool
    {
        return false === $this->shippingAddressState->getShippingAddress()->getQuote()->isVirtual();
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
