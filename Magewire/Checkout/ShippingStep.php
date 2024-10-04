<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\Step\StepInterface;
use Yireo\MagewireCheckout\Validator\ValidatorInterface;

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
