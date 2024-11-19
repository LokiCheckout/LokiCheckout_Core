<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Step\StepInterface;
use Yireo\LokiCheckout\Validator\ValidatorInterface;

class OneStep extends Component implements StepInterface
{
    public function __construct(
        private readonly ValidatorInterface $stepValidator,
    ) {
    }

    public function getCode(): string
    {
        return 'onestep';
    }

    public function getLabel(): string
    {
        return 'One Step';
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
        return true;
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
