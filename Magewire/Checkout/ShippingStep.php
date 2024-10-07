<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\Step\StepInterface;
use Yireo\FriggaCheckout\Validator\ValidatorInterface;

class ShippingStep extends Component implements StepInterface
{
    public function __construct(
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

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
